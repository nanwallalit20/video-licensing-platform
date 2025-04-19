<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Files -->
    @include('components.css')
    @yield('styles')
</head>
<body class="g-sidenav-show  bg-gray-100">
@include('components.loader')
<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <nav
                class="navbar navbar-expand-lg position-absolute top-0 z-index-3 my-3 blur blur-rounded shadow py-2 start-0 end-0 mx4">
                <div class="container-fluid container-fluid">
                    <div class="position-relative d-flex justify-content-end w-100" style="height:40px;">
                        <a class="align-items-center d-flex m-0 navbar-brand text-wrap py-0 justify-content-between position-absolute logo_block_custom"
                           href="{{route('dashboard')}}">
                            <img src="{{asset('assets/img/site_logo.png')}}" class="navbar-brand-img h-100 max-h-3rem"
                                 alt="...">

                        </a>
                        <div>
                            <h5 class="font-weight-bolder m-0">Welcome {{$user->name}}</h5>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
@yield('content')
@include('components.js')
@yield('scripts')
</body>
</html>
