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
            <h1 class="text-2xl font-semibold text-accent">@yield('page-title')</h1>
            <div class="mt-6 flex-1">@yield('content')</div>
        </main>

        <footer class="bg-black/5">
            <div class="mx-auto w-full max-w-6xl px-6 py-4 text-sm text-black/60">
                {{ __('messages.app.footer', ['year' => date('Y')]) }}
            </div>
        </footer>
    </div>

@stack('scripts')
</body>
</html>
