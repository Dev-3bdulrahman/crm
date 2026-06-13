<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Dev3bdulrahman\Crm\Services\CustomerService;
use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Models\CustomerGroup;
use Dev3bdulrahman\Crm\Models\Organization;
use Dev3bdulrahman\Crm\Models\Contact;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'tab')]
    public string $activeTab = 'customers';

    #[Url(as: 'q')]
    public string $search = '';

    // Modals
    public bool $showCustomerModal = false;
    public bool $showOrgModal = false;
    public bool $showContactModal = false;
    public bool $showGroupModal = false;

    // Customer Form Fields
    public ?int $customerId = null;
    public string $customerName = '';
    public string $customerEmail = '';
    public string $customerPhone = '';
    public string $customerAddress = '';
    public ?int $customerGroupId = null;
    public ?int $customerOrgId = null;
    public ?int $customerContactId = null;
    public string $customerStatus = 'active';

    // Organization Form Fields
    public ?int $orgId = null;
    public string $orgName = '';
    public string $orgEmail = '';
    public string $orgPhone = '';
    public string $orgWebsite = '';
    public string $orgAddress = '';
    public string $orgStatus = 'active';

    // Contact Form Fields
    public ?int $contactId = null;
    public ?int $contactOrgId = null;
    public string $contactFirstName = '';
    public string $contactLastName = '';
    public string $contactEmail = '';
    public string $contactPhone = '';
    public string $contactJobTitle = '';
    public string $contactStatus = 'active';

    // Group Form Fields
    public ?int $groupId = null;
    public string $groupName = '';

    // Lookups
    public $groups = [];
    public $organizations = [];
    public $contacts = [];

    protected $listeners = [
        'deleteCustomer' => 'deleteCustomer',
        'deleteOrg' => 'deleteOrg',
        'deleteContact' => 'deleteContact',
        'deleteGroup' => 'deleteGroup',
    ];

    #[Layout('layouts.admin')]
    public function mount()
    {
        $this->loadLookups();
    }

    public function loadLookups()
    {
        $this->groups = CustomerGroup::all();
        $this->organizations = Organization::where('status', 'active')->get();
        $this->contacts = Contact::where('status', 'active')->get();
    }

    public function updatedActiveTab()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // ─── Customer Operations ──────────────────────────────────────────────────

    public function openCustomerCreate()
    {
        $this->customerId = null;
        $this->customerName = '';
        $this->customerEmail = '';
        $this->customerPhone = '';
        $this->customerAddress = '';
        $this->customerGroupId = $this->groups->isNotEmpty() ? $this->groups->first()->id : null;
        $this->customerOrgId = null;
        $this->customerContactId = null;
        $this->customerStatus = 'active';
        $this->showCustomerModal = true;
    }

    public function openCustomerEdit($id)
    {
        $c = Customer::findOrFail($id);
        $this->customerId = $c->id;
        $this->customerName = $c->name;
        $this->customerEmail = $c->email ?? '';
        $this->customerPhone = $c->phone ?? '';
        $this->customerAddress = $c->address ?? '';
        $this->customerGroupId = $c->customer_group_id;
        $this->customerOrgId = $c->organization_id;
        $this->customerContactId = $c->contact_id;
        $this->customerStatus = $c->status;
        $this->showCustomerModal = true;
    }

    public function saveCustomer(CustomerService $service)
    {
        $rules = [
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'nullable|email|max:255',
            'customerPhone' => 'nullable|string|max:50',
            'customerAddress' => 'nullable|string|max:500',
            'customerGroupId' => 'nullable|exists:crm_customer_groups,id',
            'customerOrgId' => 'nullable|exists:crm_organizations,id',
            'customerContactId' => 'nullable|exists:crm_contacts,id',
            'customerStatus' => 'required|in:active,inactive',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->customerName,
            'email' => $this->customerEmail ?: null,
            'phone' => $this->customerPhone ?: null,
            'address' => $this->customerAddress ?: null,
            'customer_group_id' => $this->customerGroupId,
            'organization_id' => $this->customerOrgId,
            'contact_id' => $this->customerContactId,
            'status' => $this->customerStatus,
        ];

        if ($this->customerId) {
            $service->updateCustomer($this->customerId, $data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Customer updated successfully')]);
        } else {
            $service->createCustomer($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Customer created successfully')]);
        }

        $this->showCustomerModal = false;
        $this->loadLookups();
    }

    public function deleteCustomer(CustomerService $service, $id)
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $service->deleteCustomer($targetId);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Customer deleted successfully')]);
        }
    }

    // ─── Organization Operations ──────────────────────────────────────────────

    public function openOrgCreate()
    {
        $this->orgId = null;
        $this->orgName = '';
        $this->orgEmail = '';
        $this->orgPhone = '';
        $this->orgWebsite = '';
        $this->orgAddress = '';
        $this->orgStatus = 'active';
        $this->showOrgModal = true;
    }

    public function openOrgEdit($id)
    {
        $o = Organization::findOrFail($id);
        $this->orgId = $o->id;
        $this->orgName = $o->name;
        $this->orgEmail = $o->email ?? '';
        $this->orgPhone = $o->phone ?? '';
        $this->orgWebsite = $o->website ?? '';
        $this->orgAddress = $o->address ?? '';
        $this->orgStatus = $o->status;
        $this->showOrgModal = true;
    }

    public function saveOrg(CustomerService $service)
    {
        $rules = [
            'orgName' => 'required|string|max:255',
            'orgEmail' => 'nullable|email|max:255',
            'orgPhone' => 'nullable|string|max:50',
            'orgWebsite' => 'nullable|string|max:255',
            'orgAddress' => 'nullable|string|max:500',
            'orgStatus' => 'required|in:active,inactive',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->orgName,
            'email' => $this->orgEmail ?: null,
            'phone' => $this->orgPhone ?: null,
            'website' => $this->orgWebsite ?: null,
            'address' => $this->orgAddress ?: null,
            'status' => $this->orgStatus,
        ];

        if ($this->orgId) {
            $service->updateOrganization($this->orgId, $data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Organization updated successfully')]);
        } else {
            $service->createOrganization($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Organization created successfully')]);
        }

        $this->showOrgModal = false;
        $this->loadLookups();
    }

    public function deleteOrg(CustomerService $service, $id)
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $service->deleteOrganization($targetId);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Organization deleted successfully')]);
        }
    }

    // ─── Contact Operations ───────────────────────────────────────────────────

    public function openContactCreate()
    {
        $this->contactId = null;
        $this->contactOrgId = null;
        $this->contactFirstName = '';
        $this->contactLastName = '';
        $this->contactEmail = '';
        $this->contactPhone = '';
        $this->contactJobTitle = '';
        $this->contactStatus = 'active';
        $this->showContactModal = true;
    }

    public function openContactEdit($id)
    {
        $c = Contact::findOrFail($id);
        $this->contactId = $c->id;
        $this->contactOrgId = $c->organization_id;
        $this->contactFirstName = $c->first_name;
        $this->contactLastName = $c->last_name ?? '';
        $this->contactEmail = $c->email ?? '';
        $this->contactPhone = $c->phone ?? '';
        $this->contactJobTitle = $c->job_title ?? '';
        $this->contactStatus = $c->status;
        $this->showContactModal = true;
    }

    public function saveContact(CustomerService $service)
    {
        $rules = [
            'contactFirstName' => 'required|string|max:255',
            'contactLastName' => 'nullable|string|max:255',
            'contactEmail' => 'nullable|email|max:255',
            'contactPhone' => 'nullable|string|max:50',
            'contactJobTitle' => 'nullable|string|max:255',
            'contactOrgId' => 'nullable|exists:crm_organizations,id',
            'contactStatus' => 'required|in:active,inactive',
        ];

        $this->validate($rules);

        $data = [
            'first_name' => $this->contactFirstName,
            'last_name' => $this->contactLastName ?: null,
            'email' => $this->contactEmail ?: null,
            'phone' => $this->contactPhone ?: null,
            'job_title' => $this->contactJobTitle ?: null,
            'organization_id' => $this->contactOrgId,
            'status' => $this->contactStatus,
        ];

        if ($this->contactId) {
            $service->updateContact($this->contactId, $data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Contact updated successfully')]);
        } else {
            $service->createContact($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Contact created successfully')]);
        }

        $this->showContactModal = false;
        $this->loadLookups();
    }

    public function deleteContact(CustomerService $service, $id)
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $service->deleteContact($targetId);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Contact deleted successfully')]);
        }
    }

    // ─── Customer Group Operations ────────────────────────────────────────────

    public function openGroupCreate()
    {
        $this->groupId = null;
        $this->groupName = '';
        $this->showGroupModal = true;
    }

    public function openGroupEdit($id)
    {
        $g = CustomerGroup::findOrFail($id);
        $this->groupId = $g->id;
        $this->groupName = $g->name;
        $this->showGroupModal = true;
    }

    public function saveGroup(CustomerService $service)
    {
        $rules = [
            'groupName' => 'required|string|max:255',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->groupName,
        ];

        if ($this->groupId) {
            $service->updateCustomerGroup($this->groupId, $data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Customer Group updated successfully')]);
        } else {
            $service->createCustomerGroup($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Customer Group created successfully')]);
        }

        $this->showGroupModal = false;
        $this->loadLookups();
    }

    public function deleteGroup(CustomerService $service, $id)
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $service->deleteCustomerGroup($targetId);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Customer Group deleted successfully')]);
        }
    }

    public function toggleCustomerStatus(int $id): void
    {
        $customer = Customer::findOrFail($id);
        $customer->status = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->save();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Customer status updated successfully'),
        ]);
        $this->loadLookups();
    }

    public function toggleOrgStatus(int $id): void
    {
        $org = Organization::findOrFail($id);
        $org->status = $org->status === 'active' ? 'inactive' : 'active';
        $org->save();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Organization status updated successfully'),
        ]);
        $this->loadLookups();
    }

    public function toggleContactStatus(int $id): void
    {
        $contact = Contact::findOrFail($id);
        $contact->status = $contact->status === 'active' ? 'inactive' : 'active';
        $contact->save();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => __('Contact status updated successfully'),
        ]);
        $this->loadLookups();
    }

    public function render(CustomerService $service)
    {
        $filters = ['search' => $this->search];
        $items = [];

        if ($this->activeTab === 'customers') {
            $items = $service->listCustomers($filters, 10);
        } elseif ($this->activeTab === 'organizations') {
            $items = $service->listOrganizations($filters, 10);
        } elseif ($this->activeTab === 'contacts') {
            $items = $service->listContacts($filters, 10);
        }

        return view('crm::livewire.admin.customers.index', [
            'items' => $items,
        ])->title(__('Customers'));
    }
}
