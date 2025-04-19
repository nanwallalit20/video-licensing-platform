@extends('layouts.subscription')
@section('content')
<div class="container py-10">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle text-danger fa-4x mb-3"></i>
                    <h2>Payment Failed</h2>
                    <p class="text-danger">Sorry, your payment could not be processed for your title request</p>

                    @if($order)
                        <p class="mb-3">Order #{{ str_pad(1000 + $order->id, 6, '0', STR_PAD_LEFT) }}</p>
                    @endif

                    <div class="mt-4">
                        @if($order->link_expires_at > now())
                            <a href="{{ $order->payment_link }}" class="btn btn-primary me-2">
                                Try Payment Again
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            Return to Dashboard
                        </a>
                    </div>

                    @if($order->link_expires_at > now())
                        <div class="mt-3 small text-muted">
                            Payment link expires in {{ $order->link_expires_at->diffForHumans() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
