<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dev3bdulrahman\Crm\Http\Requests\CustomerStoreRequest;
use Dev3bdulrahman\Crm\Http\Resources\CustomerResource;
use Dev3bdulrahman\Crm\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerApiController extends Controller
{
    /**
     * List all customers.
     */
    public function index(Request $request, CustomerService $service): JsonResponse
    {
        $filters = $request->only(['search', 'customer_group_id', 'organization_id', 'status']);
        $perPage = (int) $request->get('per_page', 10);
        $customers = $service->listCustomers($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => __('Customers retrieved successfully'),
            'data' => CustomerResource::collection($customers->items()),
            'meta' => [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
            ],
            'errors' => []
        ]);
    }

    /**
     * Store a new customer.
     */
    public function store(CustomerStoreRequest $request, CustomerService $service): JsonResponse
    {
        $customer = $service->createCustomer($request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Customer created successfully'),
            'data' => new CustomerResource($customer),
            'errors' => []
        ], 210);
    }

    /**
     * Show customer details.
     */
    public function show($id, CustomerService $service): JsonResponse
    {
        $customer = \Dev3bdulrahman\Crm\Models\Customer::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => __('Customer details retrieved'),
            'data' => new CustomerResource($customer),
            'errors' => []
        ]);
    }

    /**
     * Update an existing customer.
     */
    public function update($id, CustomerStoreRequest $request, CustomerService $service): JsonResponse
    {
        $customer = $service->updateCustomer($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Customer updated successfully'),
            'data' => new CustomerResource($customer),
            'errors' => []
        ]);
    }

    /**
     * Delete a customer.
     */
    public function destroy($id, CustomerService $service): JsonResponse
    {
        $service->deleteCustomer($id);

        return response()->json([
            'success' => true,
            'message' => __('Customer deleted successfully'),
            'data' => null,
            'errors' => []
        ]);
    }
}
