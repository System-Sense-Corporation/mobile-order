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
        <header class="bg-accent text-white">
            @php
                $navigationLinks = [
                    ['route' => 'mobile-order', 'label' => __('messages.navigation.mobile-order')],
                    ['route' => 'orders', 'label' => __('messages.navigation.orders')],
                    ['route' => 'products', 'label' => __('messages.navigation.products')],
                    ['route' => 'customers', 'label' => __('messages.navigation.customers')],
                    ['route' => 'settings', 'label' => __('messages.navigation.settings')],
                ];
                $availableLocales = config('app.available_locales', []);
                $currentLocale = app()->getLocale();
            @endphp
            <div class="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-3 px-6 py-4">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-wide text-white">
                    {{ __('messages.app.name') }}
                </a>
                <div class="flex flex-wrap items-center gap-3 text-sm font-medium">
                    <nav class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('home') }}" class="rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white">
                            {{ __('messages.navigation.home') }}
                        </a>
                        @foreach ($navigationLinks as $item)
                            <a href="{{ route($item['route']) }}" class="rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                    @if ($availableLocales)
                        <form method="POST" action="{{ route('locale.switch') }}" class="flex items-center gap-2">
                            @csrf
                            <label for="locale" class="sr-only">{{ __('messages.app.language.label') }}</label>
                            <select id="locale" name="locale" class="form-input w-auto cursor-pointer rounded bg-white/10 text-white shadow-none ring-0 focus:border-white/70 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/50" onchange="this.form.submit()">
                                @foreach ($availableLocales as $locale)
                                    <option value="{{ $locale }}" @selected($currentLocale === $locale)>
                                        {{ __('messages.app.language.options.' . $locale) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>
            </div>
        </header>

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
