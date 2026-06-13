<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Dev3bdulrahman\Crm\Services\LeadService;
use Dev3bdulrahman\Crm\Models\LeadSource;
use Dev3bdulrahman\Crm\Models\LeadStatus;
use Dev3bdulrahman\Crm\Models\CustomerGroup;
use Dev3bdulrahman\Crm\Models\Pipeline;
use Dev3bdulrahman\Crm\Models\PipelineStage;
use App\Models\User;

class Index extends Component
{
    use WithPagination;

    // Filters
    #[Url(as: 'q')]
    public string $search = '';
    
    #[Url(as: 'source')]
    public string $sourceId = '';

    #[Url(as: 'status_step')]
    public string $statusId = '';

    #[Url(as: 'assignee')]
    public string $assigneeId = '';

    #[Url(as: 'lead_status')]
    public string $leadStatus = '';

    // Form fields
    public ?int $leadId = null;
    public string $title = '';
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $companyName = '';
    public string $value = '';
    public ?int $selectedSourceId = null;
    public ?int $selectedStatusId = null;
    public ?int $selectedContactId = null;
    public ?int $selectedAssigneeId = null;
    public string $status = 'active';

    // Modals
    public bool $showFormModal = false;
    public bool $showConvertModal = false;

    // Conversion fields
    public bool $convertToCustomer = true;
    public ?int $customerGroupId = null;
    public bool $convertToOpportunity = false;
    public string $opportunityName = '';
    public string $opportunityValue = '';
    public ?int $pipelineId = null;
    public ?int $pipelineStageId = null;
    public ?int $oppAssigneeId = null;
    public string $closeDate = '';

    // Lookup data
    public $sources = [];
    public $statuses = [];
    public $users = [];
    public $customerGroups = [];
    public $pipelines = [];
    public $stages = [];

    protected $listeners = ['delete' => 'deleteLead'];

    #[Layout('layouts.admin')]
    public function mount()
    {
        $this->sources = LeadSource::where('status', 'active')->get();
        $this->statuses = LeadStatus::where('status', 'active')->get();
        $this->users = User::all();
        $this->customerGroups = CustomerGroup::all();
        $this->pipelines = Pipeline::all();
        $this->stages = [];
    }

    public function updatedPipelineId($value)
    {
        if ($value) {
            $this->stages = PipelineStage::where('pipeline_id', $value)->orderBy('sort_order')->get();
        } else {
            $this->stages = [];
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSourceId()
    {
        $this->resetPage();
    }

    public function updatingStatusId()
    {
        $this->resetPage();
    }

    public function updatingAssigneeId()
    {
        $this->resetPage();
    }

    public function updatingLeadStatus()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->leadId = null;
        $this->title = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->companyName = '';
        $this->value = '';
        $this->selectedSourceId = null;
        $this->selectedStatusId = null;
        $this->selectedContactId = null;
        $this->selectedAssigneeId = null;
        $this->status = 'active';
    }

    public function openCreateModal()
    {
        $this->resetForm();
        // Set default status steps if available
        if ($this->statuses->isNotEmpty()) {
            $this->selectedStatusId = $this->statuses->first()->id;
        }
        if ($this->sources->isNotEmpty()) {
            $this->selectedSourceId = $this->sources->first()->id;
        }
        $this->showFormModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $lead = \Dev3bdulrahman\Crm\Models\Lead::findOrFail($id);
        
        $this->leadId = $lead->id;
        $this->title = $lead->title;
        $this->name = $lead->name;
        $this->email = $lead->email ?? '';
        $this->phone = $lead->phone ?? '';
        $this->companyName = $lead->company_name ?? '';
        $this->value = (string) $lead->value;
        $this->selectedSourceId = $lead->lead_source_id;
        $this->selectedStatusId = $lead->lead_status_id;
        $this->selectedContactId = $lead->contact_id;
        $this->selectedAssigneeId = $lead->assigned_to;
        $this->status = $lead->status;

        $this->showFormModal = true;
    }

    public function save(LeadService $service)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'companyName' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'selectedSourceId' => 'nullable|exists:crm_lead_sources,id',
            'selectedStatusId' => 'nullable|exists:crm_lead_statuses,id',
            'selectedAssigneeId' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive,converted,lost',
        ];

        $validated = $this->validate($rules);

        $data = [
            'title' => $this->title,
            'name' => $this->name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'company_name' => $this->companyName ?: null,
            'value' => $this->value !== '' ? $this->value : null,
            'lead_source_id' => $this->selectedSourceId,
            'lead_status_id' => $this->selectedStatusId,
            'assigned_to' => $this->selectedAssigneeId,
            'status' => $this->status,
        ];

        if ($this->leadId) {
            $service->updateLead($this->leadId, $data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Lead updated successfully')]);
        } else {
            $service->createLead($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Lead created successfully')]);
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function deleteLead(LeadService $service, $id)
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $service->deleteLead($targetId);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Lead deleted successfully')]);
        }
    }

    public function openConvertModal($id)
    {
        $lead = \Dev3bdulrahman\Crm\Models\Lead::findOrFail($id);
        $this->leadId = $lead->id;
        
        $this->convertToCustomer = true;
        $this->customerGroupId = $this->customerGroups->isNotEmpty() ? $this->customerGroups->first()->id : null;
        $this->convertToOpportunity = false;
        $this->opportunityName = 'صفقة: ' . ($lead->company_name ?: $lead->name);
        $this->opportunityValue = (string) $lead->value;
        $this->pipelineId = $this->pipelines->isNotEmpty() ? $this->pipelines->first()->id : null;
        
        if ($this->pipelineId) {
            $this->updatedPipelineId($this->pipelineId);
            $this->pipelineStageId = $this->stages->isNotEmpty() ? $this->stages->first()->id : null;
        }

        $this->oppAssigneeId = $lead->assigned_to ?: auth()->id();
        $this->closeDate = now()->addDays(30)->format('Y-m-d');

        $this->showConvertModal = true;
    }

    public function convert(LeadService $service)
    {
        $rules = [
            'convertToCustomer' => 'boolean',
            'customerGroupId' => 'required_if:convertToCustomer,true|nullable|exists:crm_customer_groups,id',
            'convertToOpportunity' => 'boolean',
            'opportunityName' => 'required_if:convertToOpportunity,true|string|max:255',
            'opportunityValue' => 'required_if:convertToOpportunity,true|numeric|min:0',
            'pipelineId' => 'required_if:convertToOpportunity,true|exists:crm_pipelines,id',
            'pipelineStageId' => 'required_if:convertToOpportunity,true|exists:crm_pipeline_stages,id',
            'closeDate' => 'required_if:convertToOpportunity,true|date',
        ];

        $this->validate($rules);

        $service->convertLead($this->leadId, [
            'convert_to_customer' => $this->convertToCustomer,
            'customer_group_id' => $this->customerGroupId,
            'convert_to_opportunity' => $this->convertToOpportunity,
            'opportunity_name' => $this->opportunityName,
            'opportunity_value' => $this->opportunityValue ?: 0.00,
            'pipeline_id' => $this->pipelineId,
            'pipeline_stage_id' => $this->pipelineStageId,
            'user_id' => $this->oppAssigneeId,
            'close_date' => $this->closeDate,
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => __('Lead converted successfully')]);
        $this->showConvertModal = false;
    }

    public function render(LeadService $service)
    {
        $filters = [
            'search' => $this->search,
            'lead_source_id' => $this->sourceId,
            'lead_status_id' => $this->statusId,
            'assigned_to' => $this->assigneeId,
            'status' => $this->leadStatus,
        ];

        $leadsList = $service->listLeads($filters, 10);

        return view('crm::livewire.admin.leads.index', [
            'leads' => $leadsList
        ])->title(__('Leads'));
    }
}
