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
                ['route' => 'orders',        'label' => __('messages.navigation.orders')],
                ['route' => 'products',      'label' => __('messages.navigation.products')],
                ['route' => 'customers',     'label' => __('messages.navigation.customers')],
                ['route' => 'admin.users',   'label' => __('messages.navigation.admin-users')],
                ['route' => 'settings',      'label' => __('messages.navigation.settings')],
            ];
            $availableLocales = config('app.available_locales', []);
            $currentLocale    = app()->getLocale();
        @endphp

        <div class="mx-auto flex w-full max-w-6xl flex-col gap-3 px-6 py-4">
            {{-- Top bar --}}
            <div class="flex w-full items-center justify-between gap-3">
                <a href="{{ route('home') }}" class="text-lg font-semibold tracking-wide text-white">
                    {{ __('messages.app.name') }}
                </a>

                {{-- Mobile menu toggle (md hidden) --}}
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded bg-white/10 p-2 text-white transition hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70 md:hidden"
                    data-mobile-menu-toggle
                    aria-controls="mobile-navigation-drawer"
                    aria-expanded="false"
                >
                    <span class="sr-only">{{ __('messages.navigation.toggle_menu') }}</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true" data-mobile-menu-icon="open">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6h16.5M3.75 12h16.5M3.75 18h16.5" />
                    </svg>
                    <svg class="hidden h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true" data-mobile-menu-icon="close">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- ===== Mobile drawer ===== --}}
            <div class="md:hidden" data-mobile-menu-container>
                <div class="drawer-overlay" data-mobile-menu-overlay aria-hidden="true"></div>

                <div
                    class="drawer-panel"
                    data-mobile-menu-panel
                    id="mobile-navigation-drawer"
                    aria-hidden="true"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="mobile-navigation-title"
                >
                    <div class="flex items-center justify-between">
                        <span id="mobile-navigation-title" class="text-lg font-semibold tracking-wide text-white">
                            {{ __('messages.app.name') }}
                        </span>

                        {{-- Close button (kept) --}}
                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded bg-white/10 p-2 text-white transition hover:bg-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70"
                            data-mobile-menu-close
                        >
                            <span class="sr-only">{{ __('messages.navigation.toggle_menu') }}</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Mobile nav links --}}
                    <nav class="mt-6 flex flex-col gap-2 text-sm font-medium">
                        <a href="{{ route('home') }}" class="block w-full rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70">
                            {{ __('messages.navigation.home') }}
                        </a>
                        @foreach ($navigationLinks as $item)
                            <a href="{{ route($item['route']) }}" class="block w-full rounded px-3 py-2 text-white/90 transition hover:bg-white/15 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    {{-- Mobile auth + locale --}}
                    <div class="mt-6">
                        @auth
                            <p class="text-sm text-white/90">
                                {{ __('messages.auth.logged_in_as', ['name' => auth()->user()->name]) }}
                            </p>
                            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full rounded bg-white/15 px-3 py-2 text-sm font-semibold text-white transition hover:bg-white/25 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70"
                                >
                                    {{ __('messages.auth.logout_button') }}
                                </button>
                            </form>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex w-full items-center justify-center rounded bg-white px-3 py-2 text-sm font-semibold text-accent transition hover:bg-white/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70"
                            >
                                {{ __('messages.auth.login_button') }}
                            </a>
                        @endauth
                    </div>

                    @if ($availableLocales)
                        <form method="POST" action="{{ route('locale.switch') }}" class="mt-6 flex flex-col gap-2 text-sm font-medium">
                            @csrf
                            <label for="mobile-locale" class="sr-only">{{ __('messages.app.language.label') }}</label>
                            <select
                                id="mobile-locale"
                                name="locale"
                                class="form-input w-full cursor-pointer rounded bg-white/10 text-white shadow-none ring-0 focus:border-white/70 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/50"
                                onchange="this.form.submit()"
                            >
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

            {{-- ===== Desktop navigation ===== --}}
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

                <div class="flex flex-col items-center gap-2 md:flex-row md:gap-4">
                    @if ($availableLocales)
                        <form method="POST" action="{{ route('locale.switch') }}" class="flex items-center gap-2">
                            @csrf
                            <label for="locale" class="sr-only">{{ __('messages.app.language.label') }}</label>
                            <select
                                id="locale"
                                name="locale"
                                class="form-input w-full cursor-pointer rounded bg-white/10 text-white shadow-none ring-0 focus:border-white/70 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/50 md:w-auto"
                                onchange="this.form.submit()"
                            >
                                @foreach ($availableLocales as $locale)
                                    <option value="{{ $locale }}" @selected($currentLocale === $locale)>
                                        {{ __('messages.app.language.options.' . $locale) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif

                    @auth
                        <div class="flex items-center gap-3 text-sm text-white/90">
                            <span>{{ __('messages.auth.logged_in_as', ['name' => auth()->user()->name]) }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="rounded bg-white px-3 py-2 font-semibold text-accent transition hover:bg-white/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70"
                                >
                                    {{ __('messages.auth.logout_button') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center rounded bg-white px-3 py-2 font-semibold text-accent transition hover:bg-white/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white/70"
                        >
                            {{ __('messages.auth.login_button') }}
                        </a>
                    @endauth
                </div>
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
