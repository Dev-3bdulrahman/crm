<?php

namespace Dev3bdulrahman\Crm\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Crm\Http\Requests\Api\StoreCustomerApiRequest;
use Dev3bdulrahman\Crm\Http\Requests\Api\UpdateCustomerApiRequest;
use Dev3bdulrahman\Crm\Http\Resources\CustomerResource;
use Dev3bdulrahman\Crm\Models\Customer;
use Dev3bdulrahman\Crm\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerApiController extends Controller
{
    use HasApiResponse;

    /**
     * List all customers.
     */
    public function index(Request $request, CustomerService $service): JsonResponse
    {
        $this->authorize('viewAny', Customer::class);

        $filters = $request->only(['search', 'customer_group_id', 'organization_id', 'status']);
        $perPage = (int) $request->get('per_page', 10);
        $customers = $service->listCustomers($filters, $perPage);

        return $this->success(
            CustomerResource::collection($customers->items()),
            'Customers retrieved successfully',
            200,
            [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
            ]
        );
    }

    /**
     * Store a new customer.
     */
    public function store(StoreCustomerApiRequest $request, CustomerService $service): JsonResponse
    {
        $this->authorize('create', Customer::class);

        $customer = $service->createCustomer($request->validated());

        return $this->success(
            new CustomerResource($customer),
            'Customer created successfully',
            201
        );
    }

    /**
     * Show customer details.
     */
    public function show(Customer $customer): JsonResponse
    {
        $this->authorize('view', $customer);

        $customer->load(['group', 'organization', 'contact', 'opportunities']);

        return $this->success(
            new CustomerResource($customer),
            'Customer details retrieved'
        );
    }

    /**
     * Update an existing customer.
     */
    public function update(UpdateCustomerApiRequest $request, Customer $customer, CustomerService $service): JsonResponse
    {
        $this->authorize('update', $customer);

        $customer->update($request->validated());
        $customer->refresh();

        return $this->success(
            new CustomerResource($customer),
            'Customer updated successfully'
        );
    }

    /**
     * Delete a customer.
     */
    public function destroy(Customer $customer, CustomerService $service): JsonResponse
    {
        $this->authorize('delete', $customer);

        $service->deleteCustomer($customer->id);

        return $this->success(
            null,
            'Customer deleted successfully'
        );
    }
}
