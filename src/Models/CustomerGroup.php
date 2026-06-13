<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerGroup extends Model
{
    use BelongsToCompany;

    protected $table = 'crm_customer_groups';

    protected $fillable = [
        'company_id',
        'name',
    ];

    /**
     * Customers in this group.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'customer_group_id');
    }
}
