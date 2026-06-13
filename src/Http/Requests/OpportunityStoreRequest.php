<?php

namespace Dev3bdulrahman\Crm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpportunityStoreRequest extends FormRequest
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
            'value' => 'required|numeric|min:0',
            'pipeline_id' => 'required|exists:crm_pipelines,id',
            'pipeline_stage_id' => 'required|exists:crm_pipeline_stages,id',
            'customer_id' => 'nullable|exists:crm_customers,id',
            'lead_id' => 'nullable|exists:crm_leads,id',
            'user_id' => 'nullable|exists:users,id',
            'close_date' => 'nullable|date',
            'status' => 'nullable|string|in:open,won,lost',
        ];
    }
}
