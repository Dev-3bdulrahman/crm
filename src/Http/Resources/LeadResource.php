<?php

namespace Dev3bdulrahman\Crm\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'title' => $this->title,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_name' => $this->company_name,
            'value' => $this->value,
            'status' => $this->status,
            'converted_at' => $this->converted_at,
            'lead_source' => $this->relationLoaded('source') && $this->source ? [
                'id' => $this->source->id,
                'name' => $this->source->name,
            ] : null,
            'lead_status' => $this->relationLoaded('statusStep') && $this->statusStep ? [
                'id' => $this->statusStep->id,
                'name' => $this->statusStep->name,
                'color' => $this->statusStep->color,
            ] : null,
            'assigned_to' => $this->relationLoaded('assignee') && $this->assignee ? [
                'id' => $this->assignee->id,
                'name' => $this->assignee->name,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
