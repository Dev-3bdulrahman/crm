<?php

namespace Dev3bdulrahman\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use BelongsToCompany, SoftDeletes;

    protected $table = 'crm_contacts';

    protected $fillable = [
        'company_id',
        'organization_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'job_title',
        'status',
    ];

    /**
     * Get the contact's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Parent organization.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Leads linked to this contact.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'contact_id');
    }

    /**
     * Customers linked to this contact.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'contact_id');
    }
}
