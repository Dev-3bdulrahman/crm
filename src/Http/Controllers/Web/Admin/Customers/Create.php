<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Customers;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Models\CustomerGroup;
use Dev3bdulrahman\Crm\Models\Organization;
use Dev3bdulrahman\Crm\Models\Contact;
use Dev3bdulrahman\Crm\Services\CustomerService;

class Create extends Component
{
    use AuthorizesRequests;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public ?int $customer_group_id = null;
    public ?int $organization_id = null;
    public ?int $contact_id = null;

    // Lookup data
    public $customerGroups = [];
    public $organizations = [];
    public $contacts = [];

    #[Layout('layouts.admin')]
    public function mount()
    {
        $this->authorize('create', Customer::class);

        $this->customerGroups = CustomerGroup::all();
        $this->organizations = Organization::where('status', 'active')->get();
        $this->contacts = Contact::where('status', 'active')->get();
    }

    public function save(CustomerService $service)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'customer_group_id' => 'nullable|exists:crm_customer_groups,id',
            'organization_id' => 'nullable|exists:crm_organizations,id',
            'contact_id' => 'nullable|exists:crm_contacts,id',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'address' => $this->address ?: null,
            'customer_group_id' => $this->customer_group_id,
            'organization_id' => $this->organization_id,
            'contact_id' => $this->contact_id,
            'company_id' => auth()->user()->company_id,
        ];

        $service->createCustomer($data);

        session()->flash('success', __('crm::crm.customer_created'));

        $this->redirect(route('admin.crm.customers'), navigate: true);
    }

    public function render()
    {
        return view('crm::livewire.admin.customers.create')
            ->title(__('crm::crm.create_customer'));
    }
}
