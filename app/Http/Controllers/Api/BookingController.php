<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ParkingSpot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Get all bookings
     * GET /api/bookings
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        
        // Build query with explicit eager loading of place relationship
        // This MUST be applied before get() to ensure relationships are loaded
        $query = Booking::with(['place']);

        // Filter by user if authenticated
        if (Auth::check() && $request->user()) {
            $query->where('user_id', $request->user()->id);
        }

        // Get bookings ordered by latest first with place relationship loaded
        $bookings = $query->latest()->get();

        // Current bookings: ONLY status 'active' or 'reserved' AND end_time is in the future
        $current = $bookings->filter(function ($booking) use ($now) {
            return in_array($booking->status, ['active', 'reserved']) 
                && Carbon::parse($booking->end_time)->gt($now);
        });

        // Past bookings: status 'completed' or 'cancelled', OR end_time is in the past
        $past = $bookings->filter(function ($booking) use ($now) {
            return in_array($booking->status, ['completed', 'cancelled']) 
                || Carbon::parse($booking->end_time)->lte($now);
        });

        // Relationships are automatically included in JSON response when eager loaded
        return response()->json([
            'current' => $current->values(),
            'past' => $past->values(),
        ]);
    }

    /**
     * Get a single booking by ID
     * GET /api/bookings/{id}
     */
    public function show($id)
    {
        // Find booking with all relationships needed for tracking page
        $booking = Booking::with(['place', 'parkingSpot', 'user'])
            ->find($id);

        // Check if booking exists
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        // Security check: Ensure booking belongs to authenticated user (if auth is enabled)
        if (Auth::check() && $booking->user_id && $booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this booking'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $booking
        ], 200);
    }

    /**
     * Create a new booking
     * POST /api/bookings
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'parking_spot_id' => 'required|exists:parking_spots,id',
            'duration_hours' => 'required|integer|min:1|max:168', // Max 1 week
            'vehicle_plate' => 'required|string|max:20',
            'start_time' => 'nullable|date|after_or_equal:now',
            'payment_method' => 'required|string|in:card,cash',
            'card_token' => 'required_if:payment_method,card|nullable|string',
        ]);

        $spot = ParkingSpot::findOrFail($data['parking_spot_id']);

        // Calculate booking times
        $startTime = isset($data['start_time']) 
            ? Carbon::parse($data['start_time']) 
            : Carbon::now();
        $endTime = $startTime->copy()->addHours($data['duration_hours']);

        // Check for time-based conflicts with existing active bookings
        $hasConflict = Booking::where('parking_spot_id', $spot->id)
            ->whereIn('status', ['active', 'reserved'])
            ->where(function ($query) use ($startTime, $endTime) {
                // Overlap condition: existing_start < new_end AND existing_end > new_start
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            })
            ->exists();

        if ($hasConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Parking spot is already booked for this time range',
                'requested_start' => $startTime->toISOString(),
                'requested_end' => $endTime->toISOString()
            ], 409);
        }

        // Calculate total price
        $totalPrice = $spot->price * $data['duration_hours'];

        // Normalize payment method
        $paymentMethod = strtolower($data['payment_method']);

        // Process payment if card
        if ($paymentMethod === 'card') {
            $paymentSuccess = $this->fakePayment($data['card_token'] ?? '', $totalPrice);

            if (!$paymentSuccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed. Please check your card details and try again.'
                ], 402);
            }
        }

        // Create booking in a transaction
        $booking = DB::transaction(function () use ($data, $spot, $totalPrice, $paymentMethod, $startTime, $endTime) {
            // Determine booking status
            $isImmediate = $startTime->lessThanOrEqualTo(Carbon::now()->addMinutes(5));
            $bookingStatus = $isImmediate ? 'active' : 'reserved';

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'place_id' => $spot->place_id,
                'parking_spot_id' => $spot->id,
                'vehicle_plate' => $data['vehicle_plate'],
                'duration_hours' => $data['duration_hours'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'total_price' => $totalPrice,
                'payment_method' => $paymentMethod,
                'status' => $bookingStatus,
            ]);

            // Only mark spot as unavailable if booking is immediate
            if ($isImmediate) {
                $spot->update(['is_available' => false]);
            }

            return $booking;
        });

        return response()->json([
            'success' => true,
            'message' => $paymentMethod === 'cash'
                ? 'Booking confirmed successfully. Payment required upon arrival.'
                : 'Payment successful. Booking confirmed.',
            'booking' => $booking->load(['place', 'parkingSpot', 'user']),
            'booking_details' => [
                'start_time' => $startTime->toISOString(),
                'end_time' => $endTime->toISOString(),
                'duration_hours' => $data['duration_hours'],
                'total_price' => $totalPrice,
                'payment_method' => $paymentMethod,
            ]
        ], 201);
    }

    /**
     * End a booking early (manual termination)
     * POST /api/bookings/{id}/end
     */
    public function endBooking($id)
    {
        // Find booking by ID
        $booking = Booking::with(['parkingSpot'])->find($id);

        // Check if booking exists
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        // Security check: Ensure booking belongs to authenticated user (if auth is enabled)
        if (Auth::check() && $booking->user_id && $booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this booking'
            ], 403);
        }

        // Check if booking is already completed
        if ($booking->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Booking is already completed'
            ], 400);
        }

        // Update booking status and end time in a transaction
        DB::transaction(function () use ($booking) {
            // Update booking status to completed
            $booking->update([
                'status' => 'completed',
                'end_time' => Carbon::now(),
            ]);

            // Free the parking spot
            if ($booking->parkingSpot) {
                $booking->parkingSpot->update(['is_available' => true]);
            }
        });

        // Reload booking with relationships
        $booking->refresh();
        $booking->load(['place', 'parkingSpot', 'user']);

        return response()->json([
            'success' => true,
            'message' => 'Parking session ended successfully',
            'data' => $booking
        ], 200);
    }

    /**
     * Fake payment processing (for demo purposes)
     */
    private function fakePayment($token, $amount)
    {
        // In a real application, this would integrate with a payment gateway
        return true;
    }
}
