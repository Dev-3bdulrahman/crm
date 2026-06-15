<?php

namespace Dev3bdulrahman\Crm\Policies;

use App\Models\User;
use Dev3bdulrahman\Crm\Models\Opportunity;

class OpportunityPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('crm.opportunities.view');
    }

    public function view(User $user, Opportunity $opportunity): bool
    {
        return $user->can('crm.opportunities.view') && $opportunity->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('crm.opportunities.create');
    }

    public function update(User $user, Opportunity $opportunity): bool
    {
        return $user->can('crm.opportunities.update') && $opportunity->company_id === $user->company_id;
    }

    public function delete(User $user, Opportunity $opportunity): bool
    {
        return $user->can('crm.opportunities.delete') && $opportunity->company_id === $user->company_id;
    }
}
