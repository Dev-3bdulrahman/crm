<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Crm\Http\Requests\Api\StoreOpportunityApiRequest;
use Dev3bdulrahman\Crm\Http\Requests\Api\UpdateOpportunityApiRequest;
use Dev3bdulrahman\Crm\Http\Resources\OpportunityResource;
use Dev3bdulrahman\Crm\Models\Opportunity;
use Dev3bdulrahman\Crm\Services\OpportunityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpportunityApiController extends Controller
{
    use HasApiResponse;

    /**
     * List all opportunities.
     */
    public function index(Request $request, OpportunityService $service): JsonResponse
    {
        $this->authorize('viewAny', Opportunity::class);

        $filters = $request->only(['search', 'pipeline_id', 'pipeline_stage_id', 'customer_id', 'user_id', 'status']);
        $perPage = (int) $request->get('per_page', 10);
        $opps = $service->listOpportunities($filters, $perPage);

        return $this->success(
            OpportunityResource::collection($opps->items()),
            'Opportunities retrieved successfully',
            200,
            [
                'current_page' => $opps->currentPage(),
                'last_page' => $opps->lastPage(),
                'per_page' => $opps->perPage(),
                'total' => $opps->total(),
            ]
        );
    }

    /**
     * Store a new opportunity.
     */
    public function store(StoreOpportunityApiRequest $request, OpportunityService $service): JsonResponse
    {
        $this->authorize('create', Opportunity::class);

        $opp = $service->createOpportunity($request->validated());

        return $this->success(
            new OpportunityResource($opp),
            'Opportunity created successfully',
            201
        );
    }

    /**
     * Show opportunity details.
     */
    public function show(Opportunity $opportunity): JsonResponse
    {
        $this->authorize('view', $opportunity);

        $opportunity->load(['pipeline', 'stage', 'customer', 'lead']);

        return $this->success(
            new OpportunityResource($opportunity),
            'Opportunity details retrieved'
        );
    }

    /**
     * Update an opportunity.
     */
    public function update(UpdateOpportunityApiRequest $request, Opportunity $opportunity, OpportunityService $service): JsonResponse
    {
        $this->authorize('update', $opportunity);

        $opp = $service->updateOpportunity($opportunity->id, $request->validated());

        return $this->success(
            new OpportunityResource($opp),
            'Opportunity updated successfully'
        );
    }

    /**
     * Delete an opportunity.
     */
    public function destroy(Opportunity $opportunity, OpportunityService $service): JsonResponse
    {
        $this->authorize('delete', $opportunity);

        $service->deleteOpportunity($opportunity->id);

        return $this->success(
            null,
            'Opportunity deleted successfully'
        );
    }

    /**
     * Update opportunity stage (for Kanban board/drag-drop API).
     */
    public function updateStage(Opportunity $opportunity, Request $request, OpportunityService $service): JsonResponse
    {
        $this->authorize('update', $opportunity);

        $request->validate([
            'pipeline_stage_id' => 'required|exists:crm_pipeline_stages,id',
        ]);

        $opp = $service->updateOpportunityStage($opportunity->id, $request->get('pipeline_stage_id'));

        return $this->success(
            new OpportunityResource($opp),
            'Opportunity stage updated successfully'
        );
    }
}
