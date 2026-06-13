<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\User;

class Note extends Model
{
    use BelongsToCompany;

    protected $table = 'crm_notes';

    protected $fillable = [
        'company_id',
        'user_id',
        'noteable_type',
        'noteable_id',
        'content',
    ];

    /**
     * User who wrote this note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Morph relation to target (Lead, Customer, or Opportunity).
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }
}
