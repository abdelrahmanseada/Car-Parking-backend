<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{


public function createPaymentIntent(Request $request)
{
    $spot = ParkingSpot::get();
    Stripe::setApiKey(config('services.stripe.secret'));

    $intent = PaymentIntent::create([
            'amount' => $spot->price * 100,
            'currency' => 'usd',
            'metadata' => [
        'user_id' => auth()->id(),
        'place_id' => $spot->place_id,
        'parking_spot_id' => $spot->id,
        ],
        'metadata' => [
            'garage_id' => $request->garage_id,
            'user_id' => Auth::user(),
        ],
    ]);

    return response()->json([
        'client_secret' => $intent->client_secret,
    ]);
}
}
