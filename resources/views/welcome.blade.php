<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Hotel Management System</title>

    <!-- Styles -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body class="antialiased">
    <img class="image" src="{{ asset('images/backgrounds/hotel.png') }}" alt="hotel">
    <div class="top-right">
        @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            @auth
            <a href="{{ url('/home') }}" class="link">Home</a>
            @else
            <a href="{{ route('login') }}" class="link">Log in</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="link">Register</a>
            @endif
            @endauth
        </div>
        @endif
    </div>
</body>

</html>
