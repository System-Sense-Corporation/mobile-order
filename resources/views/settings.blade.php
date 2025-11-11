@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.settings.title'))

@section('page-title', __('messages.settings.title'))

@section('content')

           @if (session('success'))
                <div class="rounded-md bg-green-50 p-4">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

    <div class="grid gap-6 lg:grid-cols-2">

        <!-- ✅ Box 1: Notification Settings -->
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-accent">{{ __('messages.settings.notifications.title') }}</h2>

            <form class="space-y-4" action="{{ route('settings.notification.update') }}" method="POST">
                @csrf

                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.order_mail') }}</span>
                    <input type="email" name="order_notification_email" class="form-input"
                           value="{{ old('order_notification_email', $settings['order_notification_email'] ?? '') }}"
                           placeholder="{{ __('messages.settings.placeholders.order_mail') }}">
                </label>

                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.alert_mail') }}</span>
                    <input type="email" name="emergency_contact_email" class="form-input"
                           value="{{ old('emergency_contact_email', $settings['emergency_contact_email'] ?? '') }}"
                           placeholder="{{ __('messages.settings.placeholders.alert_mail') }}">
                </label>

                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.slack') }}</span>
                    <input type="url" name="slack_webhook_url" class="form-input"
                           value="{{ old('slack_webhook_url', $settings['slack_webhook_url'] ?? '') }}"
                           placeholder="{{ __('messages.settings.placeholders.slack') }}">
                </label>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">{{ __('messages.settings.buttons.save') }}</button>
                </div>
            </form>
        </div>

        <!-- ✅ Box 2: System Settings -->
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-accent">{{ __('messages.settings.system.title') }}</h2>

            <form class="space-y-4" action="{{ route('settings.system.update') }}" method="POST">
                @csrf

                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.timezone') }}</span>
                    <select name="timezone" class="form-input">
                        <option value="Asia/Tokyo"         {{ ($settings['timezone'] ?? '') === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                        <option value="Asia/Bangkok"       {{ ($settings['timezone'] ?? '') === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok</option>
                        <option value="Asia/Singapore"     {{ ($settings['timezone'] ?? '') === 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore</option>
                        <option value="Asia/Ho_Chi_Minh"   {{ ($settings['timezone'] ?? '') === 'Asia/Ho_Chi_Minh' ? 'selected' : '' }}>Asia/Ho Chi Minh</option>
                    </select>
                </label>

                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.open_time') }}</span>
                    <input type="time" name="business_open_time"
                           class="form-input"
                           value="{{ old('business_open_time', $settings['business_open_time'] ?? '') }}">
                </label>

                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.close_time') }}</span>
                    <input type="time" name="business_close_time"
                           class="form-input"
                           value="{{ old('business_close_time', $settings['business_close_time'] ?? '') }}">
                </label>

                <div class="flex justify-end">
                    <button type="submit" class="btn-secondary">{{ __('messages.settings.buttons.draft') }}</button>
                </div>
            </form>
        </div>

        <!-- ✅ Box 3: Company & Email Settings (Full Width) -->
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5 lg:col-span-2">
            
            <h2 class="text-lg font-semibold text-accent">Company & Email Settings</h2>

 

            <form class="space-y-6" action="{{ route('settings.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <!-- ✅ Left column -->
                    <div class="space-y-4">
                        <h3 class="text-base font-semibold text-gray-800">Company Information</h3>

                        @php
                            $fieldsLeft = [
                                'company_name' => 'Company Name',
                                'head_office_address' => 'Head Office Address',
                                'tel' => 'Tel.',
                                'fax' => 'Fax',
                                'tax_id' => 'Tax ID',
                                'name' => 'Name',
                                'email' => 'Email',
                            ];
                        @endphp

                        @foreach ($fieldsLeft as $field => $label)
                            <label class="form-field">
                                <span class="form-label">{{ $label }}:</span>
                                <input type="text" name="{{ $field }}" class="form-input"
                                       value="{{ old($field, $settings[$field] ?? '') }}">
                            </label>
                        @endforeach
                    </div>

                    <!-- ✅ Right column -->
                    <div class="space-y-4">
                        <h3 class="text-base font-semibold text-gray-800">Email Server (SMTP)</h3>

                        @php
                            $fieldsRight = [
                                'mail_mailer' => 'MAIL_MAILER',
                                'mail_host' => 'MAIL_HOST',
                                'mail_port' => 'MAIL_PORT',
                                'mail_username' => 'MAIL_USERNAME',
                                'mail_password' => 'MAIL_PASSWORD',
                                'mail_encryption' => 'MAIL_ENCRYPTION',
                            ];
                        @endphp

                        @foreach ($fieldsRight as $field => $label)
                            <label class="form-field">
                                <span class="form-label">{{ $label }}:</span>
                                <input type="{{ $field === 'mail_password' ? 'password' : 'text' }}"
                                       name="{{ $field }}"
                                       class="form-input"
                                       value="{{ old($field, $settings[$field] ?? '') }}">
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end border-t border-gray-200 pt-5">
                    <button type="submit" class="btn-primary">Save Company & Email Settings</button>
                </div>

            </form>
        </div>

    </div>
@endsection
