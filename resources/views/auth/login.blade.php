@extends('layouts.guest')

@section('title', __('messages.auth.login_title'))

@section('content')
    <div class="mx-auto w-full max-w-md rounded-lg bg-white p-8 shadow">
        <h1 class="text-2xl font-semibold text-accent">{{ __('messages.auth.login_heading') }}</h1>

        <p class="mt-2 text-sm text-muted">
            {{ __('messages.auth.login_subheading') }}
        </p>

        {{-- ✨✨ นี่คือปุ่มที่พี่เพิ่มเข้ามาค่ะ ✨✨ --}}
        <div style="text-align: center; margin: 1.5rem 0;">
            <a href="{{ route('switch-lang', ['locale' => 'en']) }}" style="text-decoration: none; padding: 5px;">🇬🇧 EN</a> |
            <a href="{{ route('switch-lang', ['locale' => 'th']) }}" style="text-decoration: none; padding: 5px;">🇹🇭 TH</a> |
            <a href="{{ route('switch-lang', ['locale' => 'ja']) }}" style="text-decoration: none; padding: 5px;">🇯🇵 JA</a>
        </div>
        {{-- ✨✨ สิ้นสุดตรงนี้นะคะ ✨✨ --}}

        <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-text">{{ __('messages.auth.email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                    class="form-input mt-1 w-full" autofocus autocomplete="email">
                @error('email')
                    <p class="mt-2 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-text">{{ __('messages.auth.password') }}</label>
                <input id="password" name="password" type="password" class="form-input mt-1 w-full"
                    autocomplete="current-password">
                @error('password')
                    <p class="mt-2 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-text/80">
                    <input type="checkbox" name="remember" value="1" class="form-checkbox" @checked(old('remember'))>
                    <span>{{ __('messages.auth.remember_me') }}</span>
                </label>

                <a href="{{ route('home') }}" class="text-sm font-medium text-accent hover:underline">
                    {{ __('messages.navigation.home') }}
                </a>
            </div>

            <button type="submit"
                class="inline-flex w-full items-center justify-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                {{ __('messages.auth.login_button') }}
            </button>
        </form>
    </div>
@endsection