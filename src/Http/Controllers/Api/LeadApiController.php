<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Crm\Http\Requests\Api\StoreLeadApiRequest;
use Dev3bdulrahman\Crm\Http\Requests\Api\UpdateLeadApiRequest;
use Dev3bdulrahman\Crm\Http\Resources\CustomerResource;
use Dev3bdulrahman\Crm\Http\Resources\LeadResource;
use Dev3bdulrahman\Crm\Http\Resources\OpportunityResource;
use Dev3bdulrahman\Crm\Models\Lead;
use Dev3bdulrahman\Crm\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadApiController extends Controller
{
    use HasApiResponse;

    /**
     * List all leads.
     */
    public function index(Request $request, LeadService $service): JsonResponse
    {
        $this->authorize('viewAny', Lead::class);

        $filters = $request->only(['search', 'lead_source_id', 'lead_status_id', 'assigned_to', 'status']);
        $perPage = (int) $request->get('per_page', 10);
        $leads = $service->listLeads($filters, $perPage);

        return $this->success(
            LeadResource::collection($leads->items()),
            __('Leads retrieved successfully'),
            200,
            [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
            ]
        );
    }

    /**
     * Store a new lead.
     */
    public function store(StoreLeadApiRequest $request, LeadService $service): JsonResponse
    {
        $this->authorize('create', Lead::class);

        $lead = $service->createLead($request->validated());

        return $this->success(
            new LeadResource($lead),
            __('Lead created successfully'),
            201
        );
    }

    /**
     * Show a single lead.
     */
    public function show(Lead $lead, LeadService $service): JsonResponse
    {
        $this->authorize('view', $lead);

        $lead->load(['source', 'statusStep', 'assignee', 'activities', 'notes']);

        return $this->success(
            new LeadResource($lead),
            __('Lead details retrieved')
        );
    }

    /**
     * Update an existing lead.
     */
    public function update(UpdateLeadApiRequest $request, Lead $lead, LeadService $service): JsonResponse
    {
        $this->authorize('update', $lead);

        $lead = $service->updateLead($lead->id, $request->validated());

        return $this->success(
            new LeadResource($lead),
            __('Lead updated successfully')
        );
    }

    /**
     * Delete a lead.
     */
    public function destroy(Lead $lead, LeadService $service): JsonResponse
    {
        $this->authorize('delete', $lead);

        $service->deleteLead($lead->id);

        return $this->success(
            null,
            __('Lead deleted successfully')
        );
    }

    /**
     * Convert a lead to Customer and/or Opportunity.
     */
    public function convert(Lead $lead, Request $request, LeadService $service): JsonResponse
    {
        $this->authorize('update', $lead);

        $data = $request->validate([
            'convert_to_customer' => 'boolean',
            'customer_group_id' => 'nullable|exists:crm_customer_groups,id',
            'convert_to_opportunity' => 'boolean',
            'opportunity_name' => 'nullable|string|max:255',
            'opportunity_value' => 'nullable|numeric|min:0',
            'pipeline_id' => 'required_if:convert_to_opportunity,true|exists:crm_pipelines,id',
            'pipeline_stage_id' => 'required_if:convert_to_opportunity,true|exists:crm_pipeline_stages,id',
            'user_id' => 'nullable|exists:users,id',
            'close_date' => 'nullable|date',
        ]);

        $result = $service->convertLead($lead->id, $data);

        return $this->success(
            [
                'lead' => new LeadResource($result['lead']),
                'customer' => $result['customer'] ? new CustomerResource($result['customer']) : null,
                'opportunity' => $result['opportunity'] ? new OpportunityResource($result['opportunity']) : null,
            ],
            __('Lead converted successfully')
        );
    }
}
