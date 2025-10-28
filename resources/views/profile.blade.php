@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.profile.title'))

@section('page-title', __('messages.profile.title'))

@section('content')
    <div class="space-y-6">
        <p class="text-sm text-black/70">{{ __('messages.profile.description') }}</p>

        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <div class="space-y-6">
                <div class="space-y-2">
                    <h2 class="text-lg font-semibold text-accent">{{ __('messages.profile.sections.password.title') }}</h2>
                    <p class="text-sm text-black/70">{{ __('messages.profile.sections.password.description') }}</p>
                </div>
                <form class="space-y-4">
                    <label class="form-field">
                        <span class="form-label">{{ __('messages.profile.sections.password.fields.current') }}</span>
                        <input type="password" class="form-input" autocomplete="current-password">
                    </label>
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="form-field">
                            <span class="form-label">{{ __('messages.profile.sections.password.fields.new') }}</span>
                            <input type="password" class="form-input" autocomplete="new-password">
                        </label>
                        <label class="form-field">
                            <span class="form-label">{{ __('messages.profile.sections.password.fields.confirmation') }}</span>
                            <input type="password" class="form-input" autocomplete="new-password">
                        </label>
                    </div>
                    <p class="text-xs text-black/60">{{ __('messages.profile.sections.password.helper') }}</p>
                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">{{ __('messages.profile.sections.password.button') }}</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <div class="space-y-6">
                <div class="space-y-2">
                    <h2 class="text-lg font-semibold text-accent">{{ __('messages.profile.sections.account.title') }}</h2>
                    <p class="text-sm text-black/70">{{ __('messages.profile.sections.account.description') }}</p>
                </div>
                <div class="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p>{{ __('messages.profile.sections.account.delete_warning') }}</p>
                </div>
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <p class="text-xs text-black/60">{{ __('messages.profile.sections.account.support') }}</p>
                    <button type="button" class="btn-danger">{{ __('messages.profile.sections.account.button') }}</button>
                </div>
            </div>
        </section>
    </div>
@endsection
