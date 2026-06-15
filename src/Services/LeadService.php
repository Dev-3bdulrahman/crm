<?php

namespace Dev3bdulrahman\Crm\Services;

use Dev3bdulrahman\Crm\Events\CustomerCreated;
use Dev3bdulrahman\Crm\Events\LeadConverted;
use Dev3bdulrahman\Crm\Events\LeadCreated;
use Dev3bdulrahman\Crm\Models\Lead;
use Dev3bdulrahman\Crm\Models\LeadStatus;
use Dev3bdulrahman\Crm\Models\Contact;
use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Models\Opportunity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LeadService
{
    /**
     * List leads with filters and pagination.
     */
    public function listLeads(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Lead::query()->with(['source', 'statusStep', 'contact', 'assignee']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['lead_source_id'])) {
            $query->where('lead_source_id', $filters['lead_source_id']);
        }

        if (!empty($filters['lead_status_id'])) {
            $query->where('lead_status_id', $filters['lead_status_id']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new Lead.
     */
    public function createLead(array $data): Lead
    {
        $lead = Lead::create($data);

        LeadCreated::dispatch($lead, auth()->id(), $lead->company_id);

        return $lead;
    }

    /**
     * Update a Lead.
     */
    public function updateLead($id, array $data): Lead
    {
        $lead = Lead::findOrFail($id);
        $lead->update($data);
        return $lead;
    }

    /**
     * Delete a Lead.
     */
    public function deleteLead($id): bool
    {
        $lead = Lead::findOrFail($id);
        return $lead->delete();
    }

    /**
     * Convert a Lead into a Customer and/or Opportunity.
     */
    public function convertLead($id, array $data): array
    {
        $result = DB::transaction(function () use ($id, $data) {
            $lead = Lead::findOrFail($id);
            $companyId = $lead->company_id;

            // Find converted status step
            $convertedStatus = LeadStatus::where('company_id', $companyId)
                ->where('is_converted', true)
                ->first();

            $contact = null;
            $customer = null;
            $opportunity = null;

            // 1. Create Contact & Customer
            if (!empty($data['convert_to_customer'])) {
                // Check if lead already has a contact, otherwise create one
                if ($lead->contact_id) {
                    $contact = Contact::findOrFail($lead->contact_id);
                } else {
                    $nameParts = explode(' ', trim($lead->name), 2);
                    $firstName = $nameParts[0];
                    $lastName = $nameParts[1] ?? null;

                    $contact = Contact::create([
                        'company_id' => $companyId,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $lead->email,
                        'phone' => $lead->phone,
                        'job_title' => 'عميل محول',
                        'status' => 'active',
                    ]);

                    $lead->update(['contact_id' => $contact->id]);
                }

                // Create Customer record
                $customer = Customer::create([
                    'company_id' => $companyId,
                    'customer_group_id' => $data['customer_group_id'] ?? null,
                    'contact_id' => $contact->id,
                    'name' => $lead->company_name ?: $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'status' => 'active',
                ]);
            }

            // 2. Create Opportunity
            if (!empty($data['convert_to_opportunity'])) {
                $opportunity = Opportunity::create([
                    'company_id' => $companyId,
                    'pipeline_id' => $data['pipeline_id'],
                    'pipeline_stage_id' => $data['pipeline_stage_id'],
                    'customer_id' => $customer ? $customer->id : null,
                    'lead_id' => $lead->id,
                    'user_id' => $data['user_id'] ?? auth()->id(),
                    'name' => $data['opportunity_name'] ?? ($lead->title ?: 'فرصة: ' . $lead->name),
                    'value' => $data['opportunity_value'] ?? $lead->value ?? 0.00,
                    'close_date' => $data['close_date'] ?? null,
                    'status' => 'open',
                ]);
            }

            // Update Lead state to converted
            $lead->update([
                'status' => 'converted',
                'lead_status_id' => $convertedStatus ? $convertedStatus->id : $lead->lead_status_id,
                'converted_at' => now(),
            ]);

            return [
                'lead' => $lead,
                'contact' => $contact,
                'customer' => $customer,
                'opportunity' => $opportunity,
            ];
        });

        // Dispatch events after transaction commits
        $lead = $result['lead'];
        $customer = $result['customer'];
        $opportunity = $result['opportunity'];

        // Determine conversion type
        $conversionType = ($customer && $opportunity) ? 'both'
            : ($customer ? 'to_customer' : 'to_opportunity');

        LeadConverted::dispatch($lead, $customer, $opportunity, $conversionType, auth()->id());

        if ($customer) {
            CustomerCreated::dispatch($customer, 'converted_from_lead', $lead->company_id);
        }

        return $result;
    }
}
