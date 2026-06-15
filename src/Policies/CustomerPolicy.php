<?php

namespace Dev3bdulrahman\Crm\Policies;

use App\Models\User;
use Dev3bdulrahman\Crm\Models\Customer;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('crm.customers.view');
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->can('crm.customers.view') && $customer->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('crm.customers.create');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->can('crm.customers.update') && $customer->company_id === $user->company_id;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->can('crm.customers.delete') && $customer->company_id === $user->company_id;
    }
}
