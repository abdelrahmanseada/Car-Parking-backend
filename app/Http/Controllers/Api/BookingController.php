<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
{
    $spot = ParkingSpot::findOrFail($request->parking_spot_id);

    if (!$spot->is_available) {
        return response()->json(['message' => 'Slot not available'], 422);
    }

    $total = $spot->price * $request->duration_hours;

    $booking = Booking::create([
        'garage_id' => $spot->garage_id,
        'parking_spot_id' => $spot->id,
        'vehicle_plate' => $request->vehicle_plate,
        'duration_hours' => $request->duration_hours,
        'total_price' => $total,
    ]);

    $spot->update(['is_available' => false]);

    return response()->json($booking);
}


public function index(Request $request)
{
    $now = Carbon::now();

    $bookings = Booking::where('status', 'active')->get();

    $currentBookings = $bookings->filter(function ($booking) use ($now) {
        $endTime = $booking->created_at->copy()->addHours($booking->duration_hours);
        return $endTime > $now;
    });

    $pastBookings = $bookings->filter(function ($booking) use ($now) {
        $endTime = $booking->created_at->copy()->addHours($booking->duration_hours);
        return $endTime <= $now;
    });

    return response()->json([
        'current' => $currentBookings->values(),
        'past' => $pastBookings->values(),
    ]);
}


public function payAndBook(Request $request)
{
    $data = $request->validate([
        'parking_spot_id' => 'required|exists:parking_spots,id',
        'duration_hours' => 'required|integer|min:1',
        'vehicle_plate' => 'required|string|max:20',
        'card_token' => 'required|string',
    ]);

    $spot = ParkingSpot::findOrFail($data['parking_spot_id']);

    if (!$spot->is_available) {
        return response()->json([
            'message' => 'الركنة محجوزة بالفعل'
        ], 409);
    }

    $totalPrice = $spot->price * $data['duration_hours'];

    $paymentSuccess = $this->fakePayment($data['card_token'], $totalPrice);

    if (!$paymentSuccess) {
        return response()->json([
            'message' => 'فشل الدفع'
        ], 402);
    }

    DB::transaction(function () use ($data, $spot, $totalPrice) {
        // خد place_id من الركنة نفسها
        Booking::create([
            'place_id' => $spot->place_id,
            'parking_spot_id' => $spot->id,
            'vehicle_plate' => $data['vehicle_plate'],
            'duration_hours' => $data['duration_hours'],
            'total_price' => $totalPrice,
            'status' => 'active',
        ]);

        $spot->update(['is_available' => false]);
    });

    return response()->json([
        'message' => 'تم الدفع والحجز بنجاح'
    ], 201);
}
private function fakePayment($token, $amount)
{
    return true;
}

}
