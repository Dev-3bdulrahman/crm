<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dev3bdulrahman\Crm\Models\Lead;
use Dev3bdulrahman\Crm\Models\LeadSource;
use Dev3bdulrahman\Crm\Models\LeadStatus;
use Dev3bdulrahman\Crm\Services\LeadService;
use App\Models\User;

class Create extends Component
{
    use AuthorizesRequests;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $company_name = '';
    public string $title = '';
    public string $value = '';
    public ?int $lead_source_id = null;
    public ?int $lead_status_id = null;
    public ?int $assigned_to = null;

    // Lookup data
    public $sources = [];
    public $statuses = [];
    public $users = [];

    #[Layout('layouts.admin')]
    public function mount()
    {
        $this->authorize('create', Lead::class);

        $this->sources = LeadSource::where('status', 'active')->get();
        $this->statuses = LeadStatus::where('status', 'active')->get();
        $this->users = User::all();
    }

    public function save(LeadService $service)
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'company_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'lead_source_id' => 'nullable|exists:crm_lead_sources,id',
            'lead_status_id' => 'nullable|exists:crm_lead_statuses,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'company_name' => $this->company_name ?: null,
            'title' => $this->title ?: null,
            'value' => $this->value !== '' ? $this->value : null,
            'lead_source_id' => $this->lead_source_id,
            'lead_status_id' => $this->lead_status_id,
            'assigned_to' => $this->assigned_to,
            'company_id' => auth()->user()->company_id,
        ];

        $service->createLead($data);

        session()->flash('success', __('crm::crm.lead_created'));

        $this->redirect(route('admin.crm.leads'), navigate: true);
    }

    public function render()
    {
        return view('crm::livewire.admin.leads.create')
            ->title(__('crm::crm.create_lead'));
    }
}
