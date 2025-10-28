@extends('layouts.app')

@section('title', __('Forgot password'))
@section('page-title', __('Forgot password'))

@section('content')
    <div class="mx-auto w-full max-w-md rounded-lg bg-white p-8 shadow-sm">
        <p class="mb-6 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
        </p>

        @if (session('status'))
            <div class="mb-6 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="form-input w-full rounded border border-gray-200 p-2 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40" />
                @error('email')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-2">
                <a href="{{ route('login') }}" class="text-sm font-medium text-accent hover:text-accent/80">
                    {{ __('Back to login') }}
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/70">
                    {{ __('Email password reset link') }}
                </button>
            </div>
        </form>
    </div>
@endsection
