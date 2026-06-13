<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PipelineStage extends Model
{
    use BelongsToCompany;

    protected $table = 'crm_pipeline_stages';

    protected $fillable = [
        'company_id',
        'pipeline_id',
        'name',
        'probability',
        'sort_order',
    ];

    protected $casts = [
        'probability' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Parent pipeline.
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }

    /**
     * Opportunities in this pipeline stage.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'pipeline_stage_id');
    }
}
