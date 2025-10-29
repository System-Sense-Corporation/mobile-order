@extends('layouts.app')

@section('title', __('messages.admin.users.create.title'))
@section('page-title', __('messages.admin.users.create.title'))

@section('content')
    <div class="rounded-lg border border-border bg-white p-6 shadow-sm">
        <p class="text-sm text-muted">
            {{ __('messages.admin.users.create.description') }}
        </p>

        <form action="{{ route('admin.users.store') }}" method="POST" class="mt-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-text">
                    {{ __('messages.admin.users.fields.name') }}
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-input mt-2 w-full"
                    required
                >
                @error('name')
                    <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-text">
                    {{ __('messages.admin.users.fields.email') }}
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-input mt-2 w-full"
                    required
                >
                @error('email')
                    <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="password" class="block text-sm font-medium text-text">
                        {{ __('messages.admin.users.fields.password') }}
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input mt-2 w-full"
                        required
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-text">
                        {{ __('messages.admin.users.fields.password_confirmation') }}
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input mt-2 w-full"
                        required
                    >
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-text">
                    {{ __('messages.admin.users.fields.role') }}
                </label>
                <select
                    id="role"
                    name="role"
                    class="form-select mt-2 w-full"
                    required
                >
                    <option value="" disabled @selected(old('role') === null)>{{ __('messages.admin.users.fields.role_placeholder') }}</option>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col justify-end gap-3 pt-4 md:flex-row">
                <a
                    href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center justify-center rounded border border-border px-4 py-2 text-sm font-semibold text-text transition hover:bg-muted/40 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/50"
                >
                    {{ __('messages.admin.users.buttons.cancel') }}
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/50"
                >
                    {{ __('messages.admin.users.buttons.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
