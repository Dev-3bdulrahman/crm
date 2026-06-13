<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dev3bdulrahman\Crm\Http\Requests\OpportunityStoreRequest;
use Dev3bdulrahman\Crm\Http\Resources\OpportunityResource;
use Dev3bdulrahman\Crm\Services\OpportunityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OpportunityApiController extends Controller
{
    /**
     * List all opportunities.
     */
    public function index(Request $request, OpportunityService $service): JsonResponse
    {
        $filters = $request->only(['search', 'pipeline_id', 'pipeline_stage_id', 'customer_id', 'user_id', 'status']);
        $perPage = (int) $request->get('per_page', 10);
        $opps = $service->listOpportunities($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => __('Opportunities retrieved successfully'),
            'data' => OpportunityResource::collection($opps->items()),
            'meta' => [
                'current_page' => $opps->currentPage(),
                'last_page' => $opps->lastPage(),
                'per_page' => $opps->perPage(),
                'total' => $opps->total(),
            ],
            'errors' => []
        ]);
    }

    /**
     * Store a new opportunity.
     */
    public function store(OpportunityStoreRequest $request, OpportunityService $service): JsonResponse
    {
        $opp = $service->createOpportunity($request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Opportunity created successfully'),
            'data' => new OpportunityResource($opp),
            'errors' => []
        ], 210);
    }

    /**
     * Show opportunity details.
     */
    public function show($id, OpportunityService $service): JsonResponse
    {
        $opp = \Dev3bdulrahman\Crm\Models\Opportunity::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => __('Opportunity details retrieved'),
            'data' => new OpportunityResource($opp),
            'errors' => []
        ]);
    }

    /**
     * Update an opportunity.
     */
    public function update($id, OpportunityStoreRequest $request, OpportunityService $service): JsonResponse
    {
        $opp = $service->updateOpportunity($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Opportunity updated successfully'),
            'data' => new OpportunityResource($opp),
            'errors' => []
        ]);
    }

    /**
     * Delete an opportunity.
     */
    public function destroy($id, OpportunityService $service): JsonResponse
    {
        $service->deleteOpportunity($id);

        return response()->json([
            'success' => true,
            'message' => __('Opportunity deleted successfully'),
            'data' => null,
            'errors' => []
        ]);
    }

    /**
     * Update opportunity stage (for Kanban board/drag-drop API).
     */
    public function updateStage($id, Request $request, OpportunityService $service): JsonResponse
    {
        $request->validate([
            'pipeline_stage_id' => 'required|exists:crm_pipeline_stages,id',
        ]);

        $opp = $service->updateOpportunityStage($id, $request->get('pipeline_stage_id'));

        return response()->json([
            'success' => true,
            'message' => __('Opportunity stage updated successfully'),
            'data' => new OpportunityResource($opp),
            'errors' => []
        ]);
    }
}
