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
            <div class="mx-auto flex w-full max-w-6xl flex-col gap-3 px-6 py-4">
                <div class="flex w-full items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="text-lg font-semibold tracking-wide text-white">
                        {{ __('messages.app.name') }}
                    </a>
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded bg-white/10 p-2 text-white transition hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70 md:hidden"
                        data-mobile-menu-toggle
                        aria-controls="mobile-menu-drawer"
                        aria-expanded="false"
                        aria-haspopup="dialog"
                    >
                        <span class="sr-only">{{ __('messages.navigation.toggle_menu') }}</span>
                        <svg data-mobile-menu-icon="open" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6h16.5M3.75 12h16.5M3.75 18h16.5" />
                        </svg>
                        <svg data-mobile-menu-icon="close" class="hidden h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="hidden flex-col gap-3 text-sm font-medium md:flex md:flex-row md:items-center md:justify-between" id="primary-navigation">
                    <nav class="flex flex-col gap-2 md:flex-row md:items-center md:gap-2">
                        <a href="{{ route('home') }}" class="block w-full rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70 md:inline-block md:w-auto">
                            {{ __('messages.navigation.home') }}
                        </a>
                        @foreach ($navigationLinks as $item)
                            <a href="{{ route($item['route']) }}" class="block w-full rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70 md:inline-block md:w-auto">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                    @if ($availableLocales)
                        <form method="POST" action="{{ route('locale.switch') }}" class="flex items-center gap-2">
                            @csrf
                            <label for="locale" class="sr-only">{{ __('messages.app.language.label') }}</label>
                            <select id="locale" name="locale" class="form-input w-full cursor-pointer rounded bg-white/10 text-white shadow-none ring-0 focus:border-white/70 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/50 md:w-auto" onchange="this.form.submit()">
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

        <div class="md:hidden">
            <div
                class="fixed inset-0 z-40 pointer-events-none bg-black/40 opacity-0 transition-opacity duration-300 md:hidden"
                data-mobile-menu-overlay
                aria-hidden="true"
            ></div>
            <div
                id="mobile-menu-drawer"
                class="fixed inset-y-0 right-0 z-50 flex w-64 max-w-[calc(100%-3rem)] translate-x-full flex-col gap-6 rounded-l-3xl bg-white p-6 text-sm font-medium text-text shadow-2xl transition-transform duration-300 md:hidden"
                data-mobile-menu
                role="dialog"
                aria-modal="false"
                aria-labelledby="mobile-menu-title"
                aria-hidden="true"
                tabindex="-1"
            >
                <div class="flex items-center justify-between">
                    <p id="mobile-menu-title" class="text-base font-semibold text-accent">{{ __('messages.navigation.toggle_menu') }}</p>
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-full bg-accent/10 p-2 text-accent transition hover:bg-accent/15 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/50"
                        data-mobile-menu-close
                        aria-label="{{ __('messages.navigation.toggle_menu') }}"
                    >
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="flex flex-col gap-2">
                    <a href="{{ route('home') }}" class="block w-full rounded-lg px-3 py-2 text-text/80 transition hover:bg-accent/10 hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/40">
                        {{ __('messages.navigation.home') }}
                    </a>
                    @foreach ($navigationLinks as $item)
                        <a href="{{ route($item['route']) }}" class="block w-full rounded-lg px-3 py-2 text-text/80 transition hover:bg-accent/10 hover:text-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/40">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
                @if ($availableLocales)
                    <form method="POST" action="{{ route('locale.switch') }}" class="mt-auto flex flex-col gap-2">
                        @csrf
                        <label for="mobile-locale" class="text-xs font-semibold uppercase tracking-wide text-black/50">{{ __('messages.app.language.label') }}</label>
                        <select id="mobile-locale" name="locale" class="form-input w-full cursor-pointer rounded-md border border-black/10 bg-white text-sm text-text focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40" onchange="this.form.submit()">
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
