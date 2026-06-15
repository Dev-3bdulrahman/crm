<?php

namespace Dev3bdulrahman\Crm\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateOpportunityApiRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'pipeline_id' => 'sometimes|exists:crm_pipelines,id',
            'pipeline_stage_id' => 'sometimes|exists:crm_pipeline_stages,id',
            'customer_id' => 'sometimes|nullable|exists:crm_customers,id',
            'lead_id' => 'sometimes|nullable|exists:crm_leads,id',
            'value' => 'sometimes|nullable|numeric|min:0',
            'close_date' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|in:open,won,lost',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => __('crm::crm.validation_failed'),
                'data' => null,
                'meta' => [],
                'errors' => $validator->errors()->toArray(),
            ], 422)
        );
    }
}
