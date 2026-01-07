<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GarageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image
                ? 'http://localhost:8000/storage/'. $this->image
                : null,
            'description' => $this->description,

            'pricePerHour' => (float) $this->price_per_hour,

            'location' => [
                'address' => $this->address,
                'lat' => (float) $this->lat,
                'lng' => (float) $this->lng,
            ],

            'amenities' => $this->amenities ?? [],

            // تغيير اسم العلاقة
            'slots' => SlotResource::collection(
    $this->whenLoaded('parkingSpots')
),

        ];
    }
}
