<?php

namespace Dev3bdulrahman\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'customer_group_id' => 'nullable|exists:crm_customer_groups,id',
            'organization_id' => 'nullable|exists:crm_organizations,id',
            'contact_id' => 'nullable|exists:crm_contacts,id',
            'status' => 'nullable|string|in:active,inactive',
        ];
    }
}
