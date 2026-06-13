<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadSource extends Model
{
    use BelongsToCompany;

    protected $table = 'crm_lead_sources';

    protected $fillable = [
        'company_id',
        'name',
        'status',
    ];

    /**
     * Leads associated with this source.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'lead_source_id');
    }
}
