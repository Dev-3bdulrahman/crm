<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pipeline extends Model
{
    use BelongsToCompany;

    protected $table = 'crm_pipelines';

    protected $fillable = [
        'company_id',
        'name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Stages in this pipeline.
     */
    public function stages(): HasMany
    {
        return $this->hasMany(PipelineStage::class, 'pipeline_id')->orderBy('sort_order');
    }

    /**
     * Opportunities in this pipeline.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'pipeline_id');
    }
}
