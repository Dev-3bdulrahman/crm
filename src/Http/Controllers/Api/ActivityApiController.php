<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Crm\Http\Requests\Api\StoreActivityApiRequest;
use Dev3bdulrahman\Crm\Models\Activity;
use Dev3bdulrahman\Crm\Services\ActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityApiController extends Controller
{
    use HasApiResponse;

    /**
     * List activities scoped to the authenticated user's company.
     */
    public function index(Request $request, ActivityService $service): JsonResponse
    {
        $companyId = auth()->user()->company_id;
        $perPage = (int) $request->get('per_page', 15);

        $query = Activity::where('company_id', $companyId)
            ->with(['user', 'subject']);

        if ($request->filled('subject_type') && $request->filled('subject_id')) {
            $query->where('subject_type', $request->get('subject_type'))
                  ->where('subject_id', $request->get('subject_id'));
        }

        $activities = $query->orderBy('due_date', 'asc')->paginate($perPage);

        $meta = [
            'current_page' => $activities->currentPage(),
            'last_page' => $activities->lastPage(),
            'per_page' => $activities->perPage(),
            'total' => $activities->total(),
        ];

        return $this->success($activities->items(), 'Activities retrieved successfully', 200, $meta);
    }

    /**
     * Store a new activity.
     */
    public function store(StoreActivityApiRequest $request, ActivityService $service): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['company_id'] = auth()->user()->company_id;
        $data['status'] = 'pending';

        $activity = $service->createActivity($data);

        return $this->success($activity, 'Activity created successfully', 201);
    }
}
