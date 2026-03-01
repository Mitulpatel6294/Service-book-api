<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCreatedMail;
use App\Mail\QuoteSentMail;
use App\Mail\QuoteAcceptedMail;
use App\Mail\BookingConfirmedMail;
use App\Mail\BookingCancelledMail;

class BookingServices
{
    /**
     * Create a new class instance.
     */
    public function create($userId, $data, $image = null)
    {
        $exists = Booking::where('customer_id', $userId)
            ->where('service_id', $data['service_id'])
            ->where('booking_date', $data['booking_date'])
            ->where('booking_time', $data['booking_time'])
            ->whereIn('status', ['pending', 'quoted', 'accepted'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages(['booking' => 'Duplicate booking slot']);
        }

        if ($image) {
            $data['image'] = $image->store('bookings', 'public');
        }

        $service = Service::findOrFail($data['service_id']);

        $booking = Booking::create([
            'customer_id' => $userId,
            'provider_id' => $service->provider_id,
            'service_id' => $service->id,
            'booking_date' => $data['booking_date'],
            'booking_time' => $data['booking_time'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);
        $booking->load('provider', 'service', 'customer');
        Mail::to($booking->provider->email)->send(new BookingCreatedMail($booking));

        return $booking;
    }
    public function quote($bookingId, $providerId, $data)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('provider_id', $providerId)
            ->firstOrFail();

        $booking->update([
            'quoted_price' => $data['quoted_price'],
            'quoted_duration' => $data['quoted_duration'],
            'quote_status' => 'sent',
            'status' => 'quoted'
        ]);
        Mail::to($booking->customer->email)->send(new QuoteSentMail($booking));
        return $booking;
    }

    public function acceptQuote($bookingId, $userId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', $userId)
            ->firstOrFail();

        $booking->update([
            'quote_status' => 'accepted',
            'status' => 'accepted'
        ]);

        Mail::to($booking->provider->email)->send(new QuoteAcceptedMail($booking));
        return $booking;
    }

    public function rejectQuote($bookingId, $userId)
    {
        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', $userId)
            ->firstOrFail();

        $booking->update([
            'quote_status' => 'rejected',
            'status' => 'cancelled'
        ]);
        Mail::to($booking->provider->email)->send(new BookingCancelledMail($booking));
        return $booking;
    }

}
