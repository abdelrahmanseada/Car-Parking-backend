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
        // Generate a short name for mini grid display
        $fullName = $this->title ?? "Slot {$this->id}";
        $shortName = $this->title ?? (string) $this->id;
        
        return [
            'id' => $this->id,
            'name' => $fullName,
            'short_name' => $shortName, // For mini grid display in small UI boxes
            'status' => $this->is_available ? 'available' : 'occupied',
            'type' => 'standard', // Default type, can be extended later
            'floor_name' => 'Level 1', // Default floor, can be extended later
            'pricePerHour' => (float) $this->price,
        ];
    }
}
