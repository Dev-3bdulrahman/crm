<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\User;

class Lead extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $table = 'crm_leads';

    protected $fillable = [
        'company_id',
        'lead_source_id',
        'lead_status_id',
        'contact_id',
        'title',
        'name',
        'email',
        'phone',
        'company_name',
        'value',
        'assigned_to',
        'status',
        'converted_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'converted_at' => 'datetime',
    ];

    /**
     * Source of the lead.
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    /**
     * Current status of the lead.
     */
    public function statusStep(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class, 'lead_status_id');
    }

    /**
     * Associated primary contact.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * User assigned to manage this lead.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Opportunities generated from this lead.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'lead_id');
    }

    /**
     * Activities scheduled or recorded for this lead.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * History notes associated with this lead.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
