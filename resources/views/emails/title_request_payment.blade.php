@extends('emails.layout.email')
@section('title', 'MovieRites - Payment Request for Title Request #' . str_pad(1000 + $order->id, 6, '0', STR_PAD_LEFT))
@section('content')

<p>Dear <strong>{{ $order->getBuyer->name }}</strong>,</p>

    <p>We have generated a payment link for your title request. Please click the button below to complete your payment of <strong>{{ $order->currency }} {{ number_format($order->total_price, 2) }}</strong>.</p>

    <p style="text-align: center;">
        <a href="{{ $order->payment_link }}"
           style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">
            Complete Payment
        </a>
    </p>

    <p>Or you can copy and paste this link into your browser:</p>
    <p><a href="{{ $order->payment_link }}">{{ $order->payment_link }}</a></p>

    <p><strong>Please note that this payment link will expire in 72 hours.</strong></p>

    <hr>

    <h3>Order Details:</h3>
    <ul>
        <li><strong>Order ID:</strong> #{{ str_pad(1000 + $order->id, 6, '0', STR_PAD_LEFT) }}</li>
        <li><strong>Amount:</strong> {{ $order->currency }} {{ number_format($order->total_price, 2) }}</li>
        <li><strong>Due Date:</strong> {{ now()->addHours(72)->format('M d, Y H:i') }}</li>
    </ul>

    <p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>

    <p>Thanks,</p>
    <p><strong>{{ config('app.name') }}</strong></p>

    <p style="font-size: 12px; color: #777;"><em>If you did not request this payment link, please ignore this email.</em></p>
@endsection
