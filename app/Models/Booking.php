<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
     protected $fillable = [
        'place_id','parking_spot_id',
        'vehicle_plate','duration_hours',
        'total_price','status'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }
}
