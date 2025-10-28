@extends('layouts.app')

@section('title', __('Reset password'))
@section('page-title', __('Reset password'))

@section('content')
    <div class="mx-auto w-full max-w-md rounded-lg bg-white p-8 shadow-sm">
        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus
                    class="form-input w-full rounded border border-gray-200 p-2 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40" />
                @error('email')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="form-input w-full rounded border border-gray-200 p-2 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40" />
                @error('password')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm password') }}</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="form-input w-full rounded border border-gray-200 p-2 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40" />
                @error('password_confirmation')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-2">
                <a href="{{ route('login') }}" class="text-sm font-medium text-accent hover:text-accent/80">
                    {{ __('Back to login') }}
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/70">
                    {{ __('Reset password') }}
                </button>
            </div>
        </form>
    </div>
@endsection
