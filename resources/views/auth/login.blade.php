@extends('layouts.app')

@section('title', __('Log in'))
@section('page-title', __('Log in'))

@section('content')
    <div class="mx-auto w-full max-w-md rounded-lg bg-white p-8 shadow-sm">
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            @if (session('status'))
                <div class="rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="form-input w-full rounded border border-gray-200 p-2 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40" />
                @error('email')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                    class="form-input w-full rounded border border-gray-200 p-2 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40" />
                @error('password')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label for="remember" class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))
                        class="h-4 w-4 rounded border-gray-300 text-accent focus:ring-accent/60" />
                    <span>{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-accent hover:text-accent/80"
                        href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                @endif
            </div>

            <div>
                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/70">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>
@endsection
