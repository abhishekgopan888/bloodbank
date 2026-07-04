<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BloodBagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'bag_number' => $this->bag_number,
            'blood_group' => $this->blood_group,
            'donor_name' => $this->donor_name,
            'collection_date' => $this->collection_date?->toDateString(),
            'expiry_date' => $this->expiry_date?->toDateString(),
            'quantity' => $this->quantity,
            'status' => $this->status,
            'is_expired' => $this->is_expired ?? false,
            'refrigerator' => $this->whenLoaded('refrigerator', function () {
                return [
                    'id' => $this->refrigerator->id,
                    'identifier' => $this->refrigerator->identifier ?? null,
                    'blood_bank' => $this->refrigerator->bloodBank?->only(['id', 'name']),
                ];
            }),
        ];
    }
}
