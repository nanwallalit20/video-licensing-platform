@extends('layouts.subscription')

@section('styles')
<style>
     .card {
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .btn-primary {
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .custom-list {
    list-style-type: disc !important;
    padding-left: 20px; /* Adjust indentation */
    }

    .custom-list li {
        display: list-item !important; /* Ensures bullet points appear */
    }

</style>
@endsection
@section('content')
@php
   $price_id = env('SUBSRIPTION_PRICE_ID');
   $price= env('SUBSCRIPTION_PRICE');
@endphp
<div class="container py-8">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5">
            <h2 class="fw-bold mb-3">Content Library Access</h2>
            <p class="text-muted">Get unlimited access to our premium content collection</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Premium Header -->
                <div class="card-header bg-primary py-4 border-0">
                    <h3 class="text-white fw-bold mb-0">Content LIbrary Annual Plan</h3>
                    <p class="text-white text-sm  mb-0">Unlock unlimited access to a vast collection of movie and series trailers with our Content Library Annual Subscription!</p>
                </div>

                <!-- Card Body -->
                <div class="card-body p-5">
                    <!-- Price -->
                    <div class="text-center mb-5">
                        <h2 class="display-4 fw-bold mb-0">
                            {{$price}}
                            <span class="fs-6 text-muted fw-normal">/year</span>
                        </h2>
                    </div>

                    <!-- Features List -->
                    <ul class="custom-list ms-6 font-size-14 mb-5">
                        <li class="border-0 d-flex align-items-center mb-1">
                            <i class="bi bi-check-circle-fill text-primary me-3"></i>
                            <span>Full Access to Content Library</span>
                        </li>
                        <li class="border-0 d-flex align-items-center mb-1">
                            <i class="bi bi-check-circle-fill text-primary me-3"></i>
                            <span>Content Analytics Dashboard</span>
                        </li>
                        <li class="border-0 d-flex align-items-center mb-1">
                            <i class="bi bi-check-circle-fill text-primary me-3"></i>
                            <span>Complete Titles Collection</span>
                        </li>
                        <li class="border-0 d-flex align-items-center mb-1">
                            <i class="bi bi-check-circle-fill text-primary me-3"></i>
                            <span>Unlimited Trailer Access</span>
                        </li>
                        <li class="border-0 d-flex align-items-center mb-1">
                            <i class="bi bi-check-circle-fill text-primary me-3"></i>
                            <span>Priority Email Support</span>
                        </li>
                    </ul>


                    <!-- Action Button -->
                    <div class="text-center">
                        <a href="{{ route('buyer.subscription.checkout', ['userId' => $user->id, 'priceId' => $price_id]) }}"
                           class="btn btn-primary btn-lg px-5 py-3 rounded-pill fw-semibold">
                            Subscribe Now
                        </a>
                        <p class="text-muted small mt-3">
                            Secure payment processing • Instant access upon completion
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
