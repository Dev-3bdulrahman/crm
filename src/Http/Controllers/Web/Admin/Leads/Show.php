<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Leads;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dev3bdulrahman\Crm\Models\Lead;
use Dev3bdulrahman\Crm\Models\Activity;
use Dev3bdulrahman\Crm\Models\Note;
use Dev3bdulrahman\Crm\Models\CustomerGroup;
use Dev3bdulrahman\Crm\Models\Pipeline;
use Dev3bdulrahman\Crm\Models\PipelineStage;
use Dev3bdulrahman\Crm\Services\ActivityService;
use Dev3bdulrahman\Crm\Services\LeadService;

class Show extends Component
{
    use AuthorizesRequests;

    public Lead $lead;

    // Activity form properties
    public string $activityType = 'call';
    public string $activityTitle = '';
    public string $activityDescription = '';
    public string $activityDueDate = '';

    // Note form properties
    public string $noteContent = '';

    // Conversion modal properties
    public bool $showConvertModal = false;
    public bool $convertToCustomer = true;
    public ?int $customerGroupId = null;
    public bool $convertToOpportunity = false;
    public string $opportunityName = '';
    public string $opportunityValue = '';
    public ?int $pipelineId = null;
    public ?int $pipelineStageId = null;
    public ?int $oppAssigneeId = null;
    public string $closeDate = '';

    // Lookup data for conversion
    public $customerGroups = [];
    public $pipelines = [];
    public $stages = [];

    #[Layout('layouts.admin')]
    public function mount(Lead $lead)
    {
        $this->authorize('view', $lead);

        $this->lead = $lead->load(['source', 'statusStep', 'assignee', 'activities', 'notes']);

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

    public function addActivity(ActivityService $activityService)
    {
        $this->validate([
            'activityType' => 'required|in:call,meeting,task,email',
            'activityTitle' => 'required|string|max:255',
            'activityDescription' => 'nullable|string|max:1000',
            'activityDueDate' => 'nullable|date',
        ]);

        $activityService->createActivity([
            'subject_type' => Lead::class,
            'subject_id' => $this->lead->id,
            'type' => $this->activityType,
            'title' => $this->activityTitle,
            'description' => $this->activityDescription ?: null,
            'due_date' => $this->activityDueDate ?: null,
            'status' => 'pending',
            'user_id' => auth()->id(),
            'company_id' => $this->lead->company_id,
        ]);

        $this->reset(['activityType', 'activityTitle', 'activityDescription', 'activityDueDate']);
        $this->activityType = 'call';

        $this->lead->load('activities');

        $this->dispatch('notify', ['type' => 'success', 'message' => __('crm::crm.activity_added')]);
    }

    public function addNote()
    {
        $this->validate([
            'noteContent' => 'required|string|max:2000',
        ]);

        Note::create([
            'noteable_type' => Lead::class,
            'noteable_id' => $this->lead->id,
            'content' => $this->noteContent,
            'user_id' => auth()->id(),
            'company_id' => $this->lead->company_id,
        ]);

        $this->reset('noteContent');

        $this->lead->load('notes');

        $this->dispatch('notify', ['type' => 'success', 'message' => __('crm::crm.note_added')]);
    }

    public function markActivityComplete($activityId)
    {
        $activity = Activity::where('id', $activityId)
            ->where('subject_type', Lead::class)
            ->where('subject_id', $this->lead->id)
            ->firstOrFail();

        $activity->update(['status' => 'completed']);

        $this->lead->load('activities');

        $this->dispatch('notify', ['type' => 'success', 'message' => __('crm::crm.activity_completed')]);
    }

    public function openConvertModal()
    {
        $this->convertToCustomer = true;
        $this->customerGroupId = $this->customerGroups->isNotEmpty() ? $this->customerGroups->first()->id : null;
        $this->convertToOpportunity = false;
        $this->opportunityName = 'صفقة: ' . ($this->lead->company_name ?: $this->lead->name);
        $this->opportunityValue = (string) $this->lead->value;
        $this->pipelineId = $this->pipelines->isNotEmpty() ? $this->pipelines->first()->id : null;

        if ($this->pipelineId) {
            $this->updatedPipelineId($this->pipelineId);
            $this->pipelineStageId = $this->stages->isNotEmpty() ? $this->stages->first()->id : null;
        }

        $this->oppAssigneeId = $this->lead->assigned_to ?: auth()->id();
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

        $service->convertLead($this->lead->id, [
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

        $this->dispatch('notify', ['type' => 'success', 'message' => __('crm::crm.lead_converted')]);
        $this->showConvertModal = false;

        $this->lead->refresh();
    }

    public function render()
    {
        return view('crm::livewire.admin.leads.show')
            ->title(__('crm::crm.view_lead'));
    }
}
