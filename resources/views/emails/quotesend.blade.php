<h2>Quotation Sent</h2>

<p>Hello {{ $booking->customer->name }},</p>

<p>The provider has sent a quotation.</p>

<p><b>Service:</b> {{ $booking->service->title }}</p>
<p><b>Quoted Price:</b> ₹{{ $booking->quoted_price }}</p>
<p><b>Estimated Duration:</b> {{ $booking->quoted_duration }} minutes</p>