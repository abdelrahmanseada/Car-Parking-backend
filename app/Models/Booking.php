<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
     protected $fillable = [
        'user_id','place_id','parking_spot_id',
        'vehicle_plate','duration_hours',
        'start_time','end_time',
        'total_price','payment_method','status'
    ];
    
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    // Alias for place - frontend expects 'garage'
    public function garage()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class, 'parking_spot_id');
    }
}
