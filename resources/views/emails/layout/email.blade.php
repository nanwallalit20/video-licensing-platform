<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        .title-list {
            margin: 20px 0;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .title-item {
            margin: 15px 0;
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        .title-details {
            margin-left: 20px;
            color: #666;
        }
        .order-info {
            background-color: #000216;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body style="font-family: 'Blinker', sans-serif; margin-left: 60px; margin-right:60px; padding: 0; font-size: 15px;" class="main-container">
    <div style="margin: 20px auto; border-radius: 8px; max-width: 1370px;">
        <!-- Header -->
        @include('emails.partials.header')

       @yield('content')

        <!-- Footer -->
        @include('emails.partials.footer')
    </div>
</body>
</html>
