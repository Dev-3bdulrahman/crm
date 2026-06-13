<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadStatus extends Model
{
    use BelongsToCompany;

    protected $table = 'crm_lead_statuses';

    protected $fillable = [
        'company_id',
        'name',
        'color',
        'sort_order',
        'is_converted',
        'status',
    ];

    protected $casts = [
        'is_converted' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Leads associated with this status.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'lead_status_id');
    }
}
