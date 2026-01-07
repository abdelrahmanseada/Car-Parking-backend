<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
     protected $fillable = [
        'place_id','title','price','is_available'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
