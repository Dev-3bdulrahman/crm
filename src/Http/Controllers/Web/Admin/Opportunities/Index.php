<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Dev3bdulrahman\Crm\Services\OpportunityService;
use Dev3bdulrahman\Crm\Models\Pipeline;
use Dev3bdulrahman\Crm\Models\PipelineStage;
use Dev3bdulrahman\Crm\Models\Opportunity;
use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Models\Lead;
use App\Models\User;

class Index extends Component
{
    #[Url(as: 'pipeline')]
    public ?int $pipelineId = null;

    // Modals
    public bool $showOpportunityModal = false;
    public bool $showPipelineModal = false;
    public bool $showStageModal = false;

    // Opportunity fields
    public ?int $opportunityId = null;
    public string $name = '';
    public string $value = '';
    public string $closeDate = '';
    public ?int $selectedStageId = null;
    public ?int $customerId = null;
    public ?int $leadId = null;
    public ?int $userId = null; // Owner
    public string $status = 'open';

    // Pipeline fields
    public ?int $editPipelineId = null;
    public string $pipelineName = '';
    public bool $isDefault = false;

    // Stage fields
    public ?int $stageId = null;
    public string $stageName = '';
    public int $stageProbability = 0;
    public int $stageSortOrder = 0;

    // Lookups
    public $pipelines = [];
    public $stages = [];
    public $customers = [];
    public $leads = [];
    public $users = [];

    protected $listeners = [
        'deleteOpportunity' => 'deleteOpportunity',
    ];

    #[Layout('layouts.admin')]
    public function mount(OpportunityService $service)
    {
        $this->pipelines = $service->listPipelines();
        $defaultPipe = $service->getDefaultPipeline();
        
        if (!$this->pipelineId && $defaultPipe) {
            $this->pipelineId = $defaultPipe->id;
        }

        $this->loadStages($service);
        $this->loadLookups();
    }

    public function loadStages(OpportunityService $service)
    {
        if ($this->pipelineId) {
            $this->stages = $service->listStages($this->pipelineId);
        } else {
            $this->stages = [];
        }
    }

    public function loadLookups()
    {
        $this->customers = Customer::where('status', 'active')->get();
        $this->leads = Lead::where('status', '!=', 'converted')->get();
        $this->users = User::all();
    }

    public function updatedPipelineId(OpportunityService $service)
    {
        $this->loadStages($service);
    }

    // ─── Opportunity Actions ──────────────────────────────────────────────────

    public function openOpportunityCreate($stageId = null)
    {
        $this->opportunityId = null;
        $this->name = '';
        $this->value = '0.00';
        $this->closeDate = now()->addDays(30)->format('Y-m-d');
        $this->selectedStageId = $stageId ?: (is_array($this->stages) ? ($this->stages[0]['id'] ?? null) : ($this->stages->isNotEmpty() ? $this->stages->first()->id : null));
        $this->customerId = null;
        $this->leadId = null;
        $this->userId = auth()->id();
        $this->status = 'open';

        $this->showOpportunityModal = true;
    }

    public function openOpportunityEdit($id)
    {
        $opp = Opportunity::findOrFail($id);
        $this->opportunityId = $opp->id;
        $this->name = $opp->name;
        $this->value = (string) $opp->value;
        $this->closeDate = $opp->close_date ? $opp->close_date->format('Y-m-d') : '';
        $this->selectedStageId = $opp->pipeline_stage_id;
        $this->customerId = $opp->customer_id;
        $this->leadId = $opp->lead_id;
        $this->userId = $opp->user_id;
        $this->status = $opp->status;

        $this->showOpportunityModal = true;
    }

    public function saveOpportunity(OpportunityService $service)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'closeDate' => 'nullable|date',
            'selectedStageId' => 'required|exists:crm_pipeline_stages,id',
            'customerId' => 'nullable|exists:crm_customers,id',
            'leadId' => 'nullable|exists:crm_leads,id',
            'userId' => 'nullable|exists:users,id',
            'status' => 'required|in:open,won,lost',
        ];

        $this->validate($rules);

        $data = [
            'pipeline_id' => $this->pipelineId,
            'pipeline_stage_id' => $this->selectedStageId,
            'customer_id' => $this->customerId,
            'lead_id' => $this->leadId,
            'user_id' => $this->userId,
            'name' => $this->name,
            'value' => $this->value,
            'close_date' => $this->closeDate ?: null,
            'status' => $this->status,
        ];

        if ($this->opportunityId) {
            $service->updateOpportunity($this->opportunityId, $data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Opportunity updated successfully')]);
        } else {
            $service->createOpportunity($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Opportunity created successfully')]);
        }

        $this->showOpportunityModal = false;
        $this->loadStages($service);
    }

    public function moveStage(OpportunityService $service, $oppId, $stageId)
    {
        $service->updateOpportunityStage($oppId, $stageId);
        $this->loadStages($service);
        $this->dispatch('notify', ['type' => 'success', 'message' => __('Opportunity moved successfully')]);
    }

    public function deleteOpportunity(OpportunityService $service, $id)
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $service->deleteOpportunity($targetId);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('Opportunity deleted successfully')]);
            $this->loadStages($service);
        }
    }

    public function render(OpportunityService $service)
    {
        // Get opportunities grouped by stage
        $pipelineData = [];
        if ($this->pipelineId) {
            foreach ($this->stages as $stage) {
                $opps = Opportunity::where('pipeline_stage_id', $stage->id)
                    ->with(['customer', 'owner'])
                    ->get();
                $pipelineData[$stage->id] = $opps;
            }
        }

        return view('crm::livewire.admin.opportunities.index', [
            'pipelineData' => $pipelineData,
        ])->title(__('Sales Opportunities'));
    }
}
