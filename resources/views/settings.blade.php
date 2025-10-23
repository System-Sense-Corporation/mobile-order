@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.settings.title'))

@section('page-title', __('messages.settings.title'))

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-accent">{{ __('messages.settings.notifications.title') }}</h2>
            <form class="space-y-4">
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.order_mail') }}</span>
                    <input type="email" class="form-input" placeholder="{{ __('messages.settings.placeholders.order_mail') }}" value="{{ __('messages.settings.placeholders.order_mail') }}">
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.alert_mail') }}</span>
                    <input type="email" class="form-input" placeholder="{{ __('messages.settings.placeholders.alert_mail') }}">
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.slack') }}</span>
                    <input type="url" class="form-input" placeholder="{{ __('messages.settings.placeholders.slack') }}">
                </label>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">{{ __('messages.settings.buttons.save') }}</button>
                </div>
            </form>
        </div>
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-accent">{{ __('messages.settings.system.title') }}</h2>
            <form class="space-y-4">
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.timezone') }}</span>
                    <select class="form-input">
                        <option value="Asia/Tokyo">Asia/Tokyo</option>
                        <option value="Asia/Bangkok" selected>Asia/Bangkok</option>
                        <option value="Asia/Singapore">Asia/Singapore</option>
                        <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh</option>
                    </select>
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.open_time') }}</span>
                    <input type="time" class="form-input" value="05:00">
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.close_time') }}</span>
                    <input type="time" class="form-input" value="14:00">
                </label>
                <div class="flex justify-end">
                    <button type="submit" class="btn-secondary">{{ __('messages.settings.buttons.draft') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
