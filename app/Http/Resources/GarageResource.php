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
        // Handle image URL - check if external or local
        $imageUrl = null;
        if ($this->image) {
            // If it's already an external URL (starts with http/https), use as-is
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                $imageUrl = $this->image;
            } else {
                // Local file - prepend storage path
                $imageUrl = url('storage/' . $this->image);
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $imageUrl,
            'description' => $this->description,

            'pricePerHour' => (float) $this->price_per_hour,

            'availableSlots' => $this->available_slots_count ?? 0,
            'totalSlots' => $this->total_slots_count ?? 0,

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
