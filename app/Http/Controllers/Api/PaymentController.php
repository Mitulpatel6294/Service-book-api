<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function checkout($id, StripePaymentService $payment)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->customer_id !== auth()->id()) {
            throw ValidationException::withMessages(['booking' => 'Unauthorized']);
        }

        if ($booking->quote_status !== 'accepted') {
            throw ValidationException::withMessages(['payment' => 'Quote not accepted']);
        }

        $url = $payment->createCheckout($id);

        return response()->json([
            'checkout_url' => $url
        ]);
    }
}
