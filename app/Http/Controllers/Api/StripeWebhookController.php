<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BookingConfirmedMail;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid webhook'], 400);
        }

        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;
            $bookingId = $session->metadata->booking_id ?? null;


            if (!$bookingId) {  
                return response()->json(['error' => 'Missing booking id'], 400);
            }
            $booking = Booking::find(id: $bookingId);

            if (!$booking || $booking->payment_status === 'paid') {
                return response()->json(['already paid' => true]);
            }
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed'
            ]);
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->quoted_price,
                'transaction_id' => $session->payment_intent,
                'status' => 'paid',
                'paid_at' => now()
            ]);
            Mail::to($booking->customer->email)->send(new BookingConfirmedMail($booking));
            Mail::to($booking->provider->email)->send(new BookingConfirmedMail($booking));
        }
        if ($event->type === 'payment_intent.payment_failed') {

            $intent = $event->data->object;
            $bookingId = $intent->metadata->booking_id ?? null;

            $booking = Booking::find($bookingId);

            if ($booking && $booking->payment_status !== 'paid') {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'accepted'
                ]);

                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->quoted_price,
                    'transaction_id' => $intent->id,
                    'status' => 'failed'
                ]);
            }
        }
        return response()->json(['success' => true]);
    }
}
