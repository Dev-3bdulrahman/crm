<?php

namespace Dev3bdulrahman\Crm\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status,
            'group' => $this->relationLoaded('group') && $this->group ? [
                'id' => $this->group->id,
                'name' => $this->group->name,
            ] : null,
            'organization' => $this->relationLoaded('organization') && $this->organization ? [
                'id' => $this->organization->id,
                'name' => $this->organization->name,
            ] : null,
            'contact' => $this->relationLoaded('contact') && $this->contact ? [
                'id' => $this->contact->id,
                'name' => $this->contact->fullName,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
