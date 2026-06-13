<?php

namespace Dev3bdulrahman\Crm\Services;

use Dev3bdulrahman\Crm\Models\Opportunity;
use Dev3bdulrahman\Crm\Models\Pipeline;
use Dev3bdulrahman\Crm\Models\PipelineStage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OpportunityService
{
    // ─── Opportunity CRUD ─────────────────────────────────────────────────────

    public function listOpportunities(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Opportunity::query()->with(['pipeline', 'stage', 'customer', 'lead', 'owner']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['pipeline_id'])) {
            $query->where('pipeline_id', $filters['pipeline_id']);
        }

        if (!empty($filters['pipeline_stage_id'])) {
            $query->where('pipeline_stage_id', $filters['pipeline_stage_id']);
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function createOpportunity(array $data): Opportunity
    {
        return Opportunity::create($data);
    }

    public function updateOpportunity($id, array $data): Opportunity
    {
        $opp = Opportunity::findOrFail($id);
        $opp->update($data);
        return $opp;
    }

    /**
     * Update only the pipeline stage of an Opportunity (Kanban drag-and-drop).
     */
    public function updateOpportunityStage($id, $stageId): Opportunity
    {
        $opp = Opportunity::findOrFail($id);
        $stage = PipelineStage::findOrFail($stageId);
        
        $opp->update([
            'pipeline_stage_id' => $stage->id,
            'pipeline_id' => $stage->pipeline_id,
        ]);
        
        return $opp;
    }

    public function deleteOpportunity($id): bool
    {
        $opp = Opportunity::findOrFail($id);
        return $opp->delete();
    }

    // ─── Pipeline CRUD ────────────────────────────────────────────────────────

    public function listPipelines(): Collection
    {
        return Pipeline::with('stages')->get();
    }

    public function getDefaultPipeline()
    {
        return Pipeline::where('is_default', true)->with('stages')->first() 
            ?? Pipeline::with('stages')->first();
    }

    public function createPipeline(array $data): Pipeline
    {
        if (!empty($data['is_default'])) {
            // Set other pipelines default to false
            Pipeline::where('is_default', true)->update(['is_default' => false]);
        }
        return Pipeline::create($data);
    }

    public function updatePipeline($id, array $data): Pipeline
    {
        $pipeline = Pipeline::findOrFail($id);
        if (!empty($data['is_default'])) {
            Pipeline::where('is_default', true)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }
        $pipeline->update($data);
        return $pipeline;
    }

    public function deletePipeline($id): bool
    {
        $pipeline = Pipeline::findOrFail($id);
        return $pipeline->delete();
    }

    // ─── Pipeline Stage CRUD ──────────────────────────────────────────────────

    public function listStages($pipelineId): Collection
    {
        return PipelineStage::where('pipeline_id', $pipelineId)
            ->orderBy('sort_order')
            ->get();
    }

    public function createStage(array $data): PipelineStage
    {
        return PipelineStage::create($data);
    }

    public function updateStageInfo($id, array $data): PipelineStage
    {
        $stage = PipelineStage::findOrFail($id);
        $stage->update($data);
        return $stage;
    }

    public function deleteStage($id): bool
    {
        $stage = PipelineStage::findOrFail($id);
        return $stage->delete();
    }
}
