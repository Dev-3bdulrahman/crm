<?php

namespace Dev3bdulrahman\Crm\Services;

use Dev3bdulrahman\Crm\Events\CustomerCreated;
use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Models\CustomerGroup;
use Dev3bdulrahman\Crm\Models\Organization;
use Dev3bdulrahman\Crm\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{
    // ─── Customer CRUD ────────────────────────────────────────────────────────

    public function listCustomers(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Customer::query()->with(['group', 'organization', 'contact']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['customer_group_id'])) {
            $query->where('customer_group_id', $filters['customer_group_id']);
        }

        if (!empty($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function createCustomer(array $data): Customer
    {
        $customer = Customer::create($data);

        CustomerCreated::dispatch($customer, 'direct', $customer->company_id);

        return $customer;
    }

    public function updateCustomer($id, array $data): Customer
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function deleteCustomer($id): bool
    {
        $customer = Customer::findOrFail($id);
        return $customer->delete();
    }

    // ─── Organization CRUD ────────────────────────────────────────────────────

    public function listOrganizations(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Organization::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function getAllOrganizations(): Collection
    {
        return Organization::where('status', 'active')->get();
    }

    public function createOrganization(array $data): Organization
    {
        return Organization::create($data);
    }

    public function updateOrganization($id, array $data): Organization
    {
        $org = Organization::findOrFail($id);
        $org->update($data);
        return $org;
    }

    public function deleteOrganization($id): bool
    {
        $org = Organization::findOrFail($id);
        return $org->delete();
    }

    // ─── Contact CRUD ─────────────────────────────────────────────────────────

    public function listContacts(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Contact::query()->with('organization');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function getAllContacts(): Collection
    {
        return Contact::where('status', 'active')->get();
    }

    public function createContact(array $data): Contact
    {
        return Contact::create($data);
    }

    public function updateContact($id, array $data): Contact
    {
        $contact = Contact::findOrFail($id);
        $contact->update($data);
        return $contact;
    }

    public function deleteContact($id): bool
    {
        $contact = Contact::findOrFail($id);
        return $contact->delete();
    }

    // ─── Customer Group CRUD ──────────────────────────────────────────────────

    public function listCustomerGroups(): Collection
    {
        return CustomerGroup::all();
    }

    public function createCustomerGroup(array $data): CustomerGroup
    {
        return CustomerGroup::create($data);
    }

    public function updateCustomerGroup($id, array $data): CustomerGroup
    {
        $group = CustomerGroup::findOrFail($id);
        $group->update($data);
        return $group;
    }

    public function deleteCustomerGroup($id): bool
    {
        $group = CustomerGroup::findOrFail($id);
        return $group->delete();
    }
}
