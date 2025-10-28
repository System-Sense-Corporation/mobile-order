<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('messages.app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-background text-text antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-6 py-10">
        <a href="{{ route('home') }}" class="mb-8 text-xl font-semibold text-accent">
            {{ __('messages.app.name') }}
        </a>

        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </div>
</body>

</html>
