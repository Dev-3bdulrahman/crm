<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\User;

class Opportunity extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $table = 'crm_opportunities';

    protected $fillable = [
        'company_id',
        'pipeline_id',
        'pipeline_stage_id',
        'customer_id',
        'lead_id',
        'user_id',
        'name',
        'value',
        'close_date',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'close_date' => 'date',
    ];

    /**
     * Parent pipeline.
     */
    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }

    /**
     * Pipeline stage.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class, 'pipeline_stage_id');
    }

    /**
     * Linked customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Linked lead (if converted).
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    /**
     * Owner of the opportunity.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Activities history and scheduled tasks.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * History logs and notes.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
