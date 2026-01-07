<?php

namespace App\Http\Controllers\Api;

use App\Models\Place;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SlotResource;

class ParkingController extends Controller
{
    // عرض الركنات لمكان
    public function index(Place $place)
{
    return SlotResource::collection(
        $place->parkingSpots
    );
}

    // إضافة ركنة
    public function store(Request $request, Place $place)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $spot = $place->parkingSpots()->create($validated);

        return response()->json([
            'data' => $spot
        ], 201);
    }

    // حجز ركنة
    public function reserve(Place $place, ParkingSpot $spot)
    {
        if ($spot->place_id !== $place->id) {
            return response()->json(['message' => 'الركنة لا تخص هذا المكان'], 403);
        }

        if (!$spot->is_available) {
            return response()->json(['message' => 'الركنة محجوزة بالفعل'], 409);
        }

        $spot->update(['is_available' => false]);

        return response()->json(['data' => $spot]);
    }

    // فك الحجز
    public function release(Place $place, ParkingSpot $spot)
    {
        if ($spot->place_id !== $place->id) {
            return response()->json(['message' => 'الركنة لا تخص هذا المكان'], 403);
        }

        $spot->update(['is_available' => true]);

        return response()->json(['data' => $spot]);
    }

    // حذف
    public function destroy(Place $place, ParkingSpot $spot)
    {
        if ($spot->place_id !== $place->id) {
            return response()->json(['message' => 'الركنة لا تخص هذا المكان'], 403);
        }

        $spot->delete();

        return response()->json(['message' => 'Deleted']);
    }
}

