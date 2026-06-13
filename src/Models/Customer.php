<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $table = 'crm_customers';

    protected $fillable = [
        'company_id',
        'customer_group_id',
        'organization_id',
        'contact_id',
        'name',
        'email',
        'phone',
        'address',
        'status',
    ];

    /**
     * Customer group.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    /**
     * Associated organization.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Associated contact person.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * Opportunities for this customer.
     */
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'customer_id');
    }

    /**
     * Scheduled or completed activities.
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * History logs/notes.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
