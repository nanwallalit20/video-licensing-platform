@extends('emails.layout.email')
@section('title','MovieRites - Title Request Notification')
@section('content')
<div class="container">
    <p>Hello,</p>
    <h2>{{ $buyerName }}</h2>

    <p>Thank you for your title request. Your order details are below:</p>

    <div class="order-info">
        <p>Order ID: #{{ $orderUuid }}</p>
    </div>

    <div class="title-list">
        <h3>Requested Titles:</h3>
        @foreach($titles as $title)
            <div class="title-item">
                <strong>{{ $title['name'] }}</strong>
                <div class="title-details">
                    <p>Type: {{ ucfirst($title['type']) }}</p>
                    @if(isset($title['season_name']))
                        <p>Season: {{ $title['season_name'] }}</p>
                    @endif
                    <p>Genres: {{ $title['genres'] ?? 'N/A' }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
<p>If you have any questions or concerns, please don't hesitate to contact our support team.</p>

<p>Thanks,</p>
<p><strong>{{ config('app.name') }}</strong></p>
@endsection
