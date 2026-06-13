<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $table = 'crm_organizations';

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'website',
        'address',
        'status',
    ];

    /**
     * Contacts belonging to this organization.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'organization_id');
    }

    /**
     * Customers belonging to this organization.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'organization_id');
    }
}
