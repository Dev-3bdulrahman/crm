<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Web\Admin\Opportunities;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dev3bdulrahman\Crm\Models\Opportunity;
use Dev3bdulrahman\Crm\Models\Pipeline;
use Dev3bdulrahman\Crm\Models\PipelineStage;
use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Models\Lead;
use Dev3bdulrahman\Crm\Services\OpportunityService;

class Create extends Component
{
    use AuthorizesRequests;

    public string $name = '';
    public ?int $pipeline_id = null;
    public ?int $pipeline_stage_id = null;
    public ?int $customer_id = null;
    public ?int $lead_id = null;
    public string $value = '';
    public string $close_date = '';
    public string $status = 'open';

    // Lookup data
    public $pipelines = [];
    public $stages = [];
    public $customers = [];
    public $leads = [];

    #[Layout('layouts.admin')]
    public function mount()
    {
        $this->authorize('create', Opportunity::class);

        $this->pipelines = Pipeline::all();
        $this->customers = Customer::where('status', 'active')->get();
        $this->leads = Lead::where('status', '!=', 'converted')->get();
        $this->stages = [];
    }

    public function updatedPipelineId($value)
    {
        if ($value) {
            $this->stages = PipelineStage::where('pipeline_id', $value)->orderBy('sort_order')->get();
        } else {
            $this->stages = [];
        }
        $this->pipeline_stage_id = null;
    }

    public function save(OpportunityService $service)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'pipeline_id' => 'required|exists:crm_pipelines,id',
            'pipeline_stage_id' => 'required|exists:crm_pipeline_stages,id',
            'customer_id' => 'nullable|exists:crm_customers,id',
            'lead_id' => 'nullable|exists:crm_leads,id',
            'value' => 'nullable|numeric|min:0',
            'close_date' => 'nullable|date',
            'status' => 'nullable|in:open,won,lost',
        ]);

        $data = [
            'name' => $this->name,
            'pipeline_id' => $this->pipeline_id,
            'pipeline_stage_id' => $this->pipeline_stage_id,
            'customer_id' => $this->customer_id,
            'lead_id' => $this->lead_id,
            'value' => $this->value !== '' ? $this->value : null,
            'close_date' => $this->close_date ?: null,
            'status' => $this->status ?: 'open',
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->id(),
        ];

        $service->createOpportunity($data);

        session()->flash('success', __('crm::crm.opportunity_created'));

        $this->redirect(route('admin.crm.opportunities'), navigate: true);
    }

    public function render()
    {
        return view('crm::livewire.admin.opportunities.create')
            ->title(__('crm::crm.create_opportunity'));
    }
}
