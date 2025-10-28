@extends('layouts.guest')

@section('title', __('messages.auth.login_title'))

@section('content')
    <div class="menu-card space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-accent">{{ __('messages.auth.login_heading') }}</h1>
                <p class="mt-2 text-sm text-black/60">
                    {{ __('messages.auth.login_subheading') }}
                </p>
            </div>

            <a href="{{ route('home') }}" class="btn-secondary whitespace-nowrap">
                {{ __('messages.navigation.home') }}
            </a>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <div class="form-field">
                <label for="email" class="form-label">{{ __('messages.auth.email') }}</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    class="form-input"
                    required
                    autofocus
                    autocomplete="email"
                >
                @error('email')
                    <p class="text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="password" class="form-label">{{ __('messages.auth.password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-input"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <p class="text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="flex items-center gap-2 text-sm text-black/70">
                    <input type="checkbox" name="remember" value="1" class="form-checkbox" @checked(old('remember'))>
                    <span>{{ __('messages.auth.remember_me') }}</span>
                </label>

                <a href="{{ route('home') }}" class="text-sm font-semibold text-accent hover:underline">
                    {{ __('messages.navigation.home') }}
                </a>
            </div>

            <button type="submit" class="btn-primary w-full">
                {{ __('messages.auth.login_button') }}
            </button>
        </form>
    </div>
@endsection
