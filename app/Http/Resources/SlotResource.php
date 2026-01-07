<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
       public function toArray(Request $request): array
    {
        return [
            'id' => 'slot-' . $this->id,          
            'number' => (string) $this->id,
            'status' => $this->is_available
                ? 'available'
                : 'occupied',

            'level' => 1,
            'vehicleSize' => 'standard',
            'pricePerHour' => (float) $this->price,
        ];
    }
}
