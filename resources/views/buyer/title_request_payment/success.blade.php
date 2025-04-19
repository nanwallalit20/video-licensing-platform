@extends('layouts.subscription')

@section('content')
<div class="container py-10">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                    <h2>Payment Successful!</h2>
                    <p>Thank you for your payment of {{ $order->total_price }} {{ $order->currency }}</p>
                    <p>Order ID: #{{ str_pad(1000 + $order->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
