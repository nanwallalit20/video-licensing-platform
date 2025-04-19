@extends('layouts.subscription')

@section('content')
<div class="container py-10">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                    <h2>Payment Successful!</h2>
                    <p>Your subscription has been successfully completed. You can now log in to your account.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                        Login to your account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
