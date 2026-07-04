<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BloodBankResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'refrigerators_count' => $this->when(isset($this->refrigerators_count), $this->refrigerators_count),
        ];
    }
}
