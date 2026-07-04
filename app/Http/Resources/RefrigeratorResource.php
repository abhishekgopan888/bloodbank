<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RefrigeratorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'identifier' => $this->identifier,
            'blood_bank' => $this->whenLoaded('bloodBank', function () {
                return [
                    'id' => $this->bloodBank->id,
                    'name' => $this->bloodBank->name,
                ];
            }),
            'status' => $this->status,
        ];
    }
}
