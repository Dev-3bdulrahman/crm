<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dev3bdulrahman\Crm\Http\Requests\LeadStoreRequest;
use Dev3bdulrahman\Crm\Http\Resources\LeadResource;
use Dev3bdulrahman\Crm\Services\LeadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LeadApiController extends Controller
{
    /**
     * List all leads.
     */
    public function index(Request $request, LeadService $service): JsonResponse
    {
        $filters = $request->only(['search', 'lead_source_id', 'lead_status_id', 'assigned_to', 'status']);
        $perPage = (int) $request->get('per_page', 10);
        $leads = $service->listLeads($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => __('Leads retrieved successfully'),
            'data' => LeadResource::collection($leads->items()),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
            ],
            'errors' => []
        ]);
    }

    /**
     * Store a new lead.
     */
    public function store(LeadStoreRequest $request, LeadService $service): JsonResponse
    {
        $lead = $service->createLead($request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Lead created successfully'),
            'data' => new LeadResource($lead),
            'errors' => []
        ], 210); // 201 Created status
    }

    /**
     * Show a single lead.
     */
    public function show($id, LeadService $service): JsonResponse
    {
        $lead = $service->listLeads()->getCollection()->firstWhere('id', $id);
        if (!$lead) {
            $lead = \Dev3bdulrahman\Crm\Models\Lead::findOrFail($id);
        }

        return response()->json([
            'success' => true,
            'message' => __('Lead details retrieved'),
            'data' => new LeadResource($lead),
            'errors' => []
        ]);
    }

    /**
     * Update an existing lead.
     */
    public function update($id, LeadStoreRequest $request, LeadService $service): JsonResponse
    {
        $lead = $service->updateLead($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Lead updated successfully'),
            'data' => new LeadResource($lead),
            'errors' => []
        ]);
    }

    /**
     * Delete a lead.
     */
    public function destroy($id, LeadService $service): JsonResponse
    {
        $service->deleteLead($id);

        return response()->json([
            'success' => true,
            'message' => __('Lead deleted successfully'),
            'data' => null,
            'errors' => []
        ]);
    }

    /**
     * Convert a lead to Customer and/or Opportunity.
     */
    public function convert($id, Request $request, LeadService $service): JsonResponse
    {
        $data = $request->validate([
            'convert_to_customer' => 'boolean',
            'customer_group_id' => 'nullable|exists:crm_customer_groups,id',
            'convert_to_opportunity' => 'boolean',
            'opportunity_name' => 'nullable|string|max:255',
            'opportunity_value' => 'nullable|numeric|min:0',
            'pipeline_id' => 'required_if:convert_to_opportunity,true|exists:crm_pipelines,id',
            'pipeline_stage_id' => 'required_if:convert_to_opportunity,true|exists:crm_pipeline_stages,id',
            'user_id' => 'nullable|exists:users,id',
            'close_date' => 'nullable|date',
        ]);

        $result = $service->convertLead($id, $data);

        return response()->json([
            'success' => true,
            'message' => __('Lead converted successfully'),
            'data' => [
                'lead' => new LeadResource($result['lead']),
                'customer' => $result['customer'] ? new CustomerResource($result['customer']) : null,
                'opportunity' => $result['opportunity'] ? new OpportunityResource($result['opportunity']) : null,
            ],
            'errors' => []
        ]);
    }
}
