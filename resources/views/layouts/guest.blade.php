<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('messages.app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-background text-text antialiased">
    <div class="flex min-h-screen flex-col">
        @include('layouts.partials.header')

        <main class="mx-auto flex w-full max-w-6xl flex-1 flex-col px-6 py-10">
            <div class="flex flex-1 items-center justify-center py-10">
                <div class="w-full max-w-md">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>

</html>
