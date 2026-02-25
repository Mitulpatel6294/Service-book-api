<h2>New Booking Request</h2>

<p>Hello {{ $booking->provider->name }},</p>

<p>A new booking has been created.</p>

<p><b>Service:</b> {{ $booking->service->title }}</p>
<p><b>Date:</b> {{ $booking->booking_date }} {{ $booking->booking_time }}</p>
<p><b>Notes:</b> {{ $booking->notes }}</p>