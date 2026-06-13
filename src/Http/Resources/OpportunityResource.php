<?php

namespace Dev3bdulrahman\Crm\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'value' => $this->value,
            'close_date' => $this->close_date,
            'status' => $this->status,
            'pipeline' => $this->relationLoaded('pipeline') && $this->pipeline ? [
                'id' => $this->pipeline->id,
                'name' => $this->pipeline->name,
            ] : null,
            'stage' => $this->relationLoaded('stage') && $this->stage ? [
                'id' => $this->stage->id,
                'name' => $this->stage->name,
                'probability' => $this->stage->probability,
            ] : null,
            'customer' => $this->relationLoaded('customer') && $this->customer ? [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
            ] : null,
            'lead' => $this->relationLoaded('lead') && $this->lead ? [
                'id' => $this->lead->id,
                'name' => $this->lead->name,
            ] : null,
            'owner' => $this->relationLoaded('owner') && $this->owner ? [
                'id' => $this->owner->id,
                'name' => $this->owner->name,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
