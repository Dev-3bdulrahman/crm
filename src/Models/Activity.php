<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;

class Activity extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $table = 'crm_activities';

    protected $fillable = [
        'company_id',
        'user_id',
        'subject_type',
        'subject_id',
        'type',
        'title',
        'description',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * User assigned/responsible.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Morph relation to the activity subject (Lead, Customer, or Opportunity).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
