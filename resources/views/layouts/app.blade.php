@php
    use App\Enums\Roles;
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.ico')}}">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"/>
    <!-- Nucleo Icons -->
    <link href="{{asset('/assets/css/nucleo-icons.css')}}" rel="stylesheet"/>
    <link href="{{asset('/assets/css/nucleo-svg.css')}}" rel="stylesheet"/>
    <!-- CSS Files -->
    @include('components.css')
    @yield('styles')
</head>
<body class="g-sidenav-show bg-gray-100 ">
@include('components.loader')
@if(Auth::user()->getUserProfile->role_id->value == Roles::Superadmin->value)
    @include('components.navbar.admin')
@elseif (Auth::user()->getUserProfile->role_id->value == Roles::Seller->value)
    @include('components.navbar.seller')
@else
    @include('components.navbar.buyer')
@endif
<main class="main-content position-relative">
    <div class="container-fluid py-4">
        <div class="top-nave-bar d-flex justify-content-between mb-4 align-items-center">
            <div class="left-heading-block">
                <h6 class="font-weight-bolder mb-0 text-capitalize">
                    @yield('title', 'Default Page Title')
                </h6>
            </div>
            <div class="right-heading-block d-flex align-items-center">

                @if (Session::get('old_user_login'))
                    <a href="{{ route('switchAccount') }}" class="btn m-2 p-2 btn-sm ms-3 bg-gradient-admin-blue">Return to Main
                        Dashboard</a>
                @endif
            </div>
        </div>
        @yield('content')
        @include('backend.titles. models.title_profile_modal')
    </div>
    @include('components.footer')
</main>
@include('components.js')
@yield('scripts')

</body>

</html>
