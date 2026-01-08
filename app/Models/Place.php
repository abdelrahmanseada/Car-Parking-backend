<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price_per_hour',
        'image', 'amenities', 'address', 'lat', 'lng'
    ];

   
    protected $casts = [
        'amenities' => 'array',
        'lat' => 'float',       
        'lng' => 'float',      
        'price_per_hour' => 'integer',
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

       
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Local file - prepend storage path
        return asset('storage/' . $this->image);
    }

    
    public function getCoordinatesAttribute()
    {
        return [
            'lat' => $this->lat,
            'lng' => $this->lng
        ];
    }

    public function parkingSpots()
    {
        return $this->hasMany(ParkingSpot::class);
    }

   
    public function getGoogleMapsLinkAttribute()
    {
        if ($this->google_maps_url ?? false) { 
            return $this->google_maps_url;
        }

        return "https://www.google.com/maps/search/?api=1&query={$this->lat},{$this->lng}";
    }
}