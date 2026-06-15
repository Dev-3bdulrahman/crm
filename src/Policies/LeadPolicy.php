<?php

namespace Dev3bdulrahman\Crm\Policies;

use App\Models\User;
use Dev3bdulrahman\Crm\Models\Lead;

class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('crm.leads.view');
    }

    public function view(User $user, Lead $lead): bool
    {
        return $user->can('crm.leads.view') && $lead->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('crm.leads.create');
    }

    public function update(User $user, Lead $lead): bool
    {
        return $user->can('crm.leads.update') && $lead->company_id === $user->company_id;
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->can('crm.leads.delete') && $lead->company_id === $user->company_id;
    }

    public function export(User $user): bool
    {
        return $user->can('crm.leads.export');
    }
}
