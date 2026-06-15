<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Pipeline;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Dev3bdulrahman\Crm\Models\Opportunity;
use Dev3bdulrahman\Crm\Models\Pipeline;
use Dev3bdulrahman\Crm\Models\PipelineStage;
use Dev3bdulrahman\Crm\Services\OpportunityService;

class Kanban extends Component
{
    public ?int $selectedPipelineId = null;
    public $pipelines = [];
    public $stages = [];
    public $opportunities = [];

    #[Layout('layouts.admin')]
    public function mount()
    {
        $this->authorize('viewAny', Opportunity::class);

        $this->pipelines = Pipeline::where('company_id', auth()->user()->company_id)->get();

        if ($this->pipelines->isNotEmpty()) {
            $this->selectedPipelineId = $this->pipelines->first()->id;
            $this->loadPipelineData();
        }
    }

    public function loadPipelineData()
    {
        $companyId = auth()->user()->company_id;

        $this->stages = PipelineStage::where('pipeline_id', $this->selectedPipelineId)
            ->orderBy('sort_order')
            ->get();

        $opportunities = Opportunity::where('pipeline_id', $this->selectedPipelineId)
            ->where('company_id', $companyId)
            ->with(['customer', 'stage'])
            ->get();

        $this->opportunities = $opportunities->groupBy('pipeline_stage_id')->toArray();
    }

    public function updatedSelectedPipelineId()
    {
        $this->loadPipelineData();
    }

    public function moveOpportunity($opportunityId, $newStageId)
    {
        $opportunity = Opportunity::findOrFail($opportunityId);

        $this->authorize('update', $opportunity);

        app(OpportunityService::class)->updateOpportunity($opportunityId, [
            'pipeline_stage_id' => $newStageId,
        ]);

        $this->loadPipelineData();
    }

    public function render()
    {
        return view('crm::livewire.admin.pipeline.kanban')
            ->title(__('crm::crm.kanban'));
    }
}
