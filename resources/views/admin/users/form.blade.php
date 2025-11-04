@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.admin_users.create.title'))

@section('page-title', __('messages.admin_users.create.title'))

@section('content')
    <div class="space-y-6">
        <p class="text-sm text-black/70">{{ __('messages.admin_users.create.description') }}</p>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">{{ __('messages.admin_users.form.validation_error_heading') }}</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('admin.users.store') }}"
            method="POST"
            class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5"
        >
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="flex items-center gap-1 text-sm font-medium text-black/80">
                        {{ __('messages.admin_users.form.name.label') }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="form-input mt-2 w-full rounded border border-black/10 bg-white px-3 py-2 text-sm text-black shadow-none focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="flex items-center gap-1 text-sm font-medium text-black/80">
                        {{ __('messages.admin_users.form.email.label') }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="form-input mt-2 w-full rounded border border-black/10 bg-white px-3 py-2 text-sm text-black shadow-none focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="flex items-center gap-1 text-sm font-medium text-black/80">
                        {{ __('messages.admin_users.form.phone.label') }}
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-input mt-2 w-full rounded border border-black/10 bg-white px-3 py-2 text-sm text-black shadow-none focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40"
                        placeholder="{{ __('messages.admin_users.form.phone.placeholder') }}"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="department" class="flex items-center gap-1 text-sm font-medium text-black/80">
                        {{ __('messages.admin_users.form.department.label') }}
                        <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="department"
                        name="department"
                        required
                        class="form-select mt-2 w-full rounded border border-black/10 bg-white px-3 py-2 text-sm text-black shadow-none focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40"
                    >
                        <option value="" disabled @selected(! old('department'))>{{ __('messages.admin_users.form.department.placeholder') }}</option>
                        @foreach (trans('messages.admin_users.form.department.options') as $value => $label)
                            <option value="{{ $value }}" @selected(old('department') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('department')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <span class="text-sm font-medium text-black/80">{{ __('messages.admin_users.form.authority.label') }} <span class="text-red-500">*</span></span>
                <div class="mt-3 grid gap-3 md:grid-cols-3">
                    @foreach (trans('messages.admin_users.roles') as $value => $label)
                        <label class="flex items-start gap-2 rounded border border-black/10 p-3 text-sm text-black/80">
                            <input
                                type="radio"
                                name="authority"
                                value="{{ $value }}"
                                @checked(old('authority', 'editor') === $value)
                                required
                                class="mt-1 h-4 w-4 border-black/30 text-accent focus:ring-accent"
                            >
                            <span>
                                <span class="font-medium text-black/90">{{ $label }}</span>
                                <span class="block text-xs text-black/60">{{ __('messages.admin_users.role_descriptions.' . $value) }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('authority')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <label class="flex items-start gap-3 rounded border border-black/10 bg-black/2 p-4 text-sm text-black/80">
                    <input
                        type="checkbox"
                        name="notify_new_orders"
                        value="1"
                        @checked(old('notify_new_orders', true))
                        class="mt-1 h-4 w-4 rounded border-black/30 text-accent focus:ring-accent"
                    >
                    <span>
                        <span class="font-medium text-black/90">{{ __('messages.admin_users.form.notify_new_orders.label') }}</span>
                        <span class="block text-xs text-black/60">{{ __('messages.admin_users.form.notify_new_orders.help') }}</span>
                    </span>
                </label>

                <label class="flex items-start gap-3 rounded border border-black/10 bg-black/2 p-4 text-sm text-black/80">
                    <input
                        type="checkbox"
                        name="require_password_change"
                        value="1"
                        @checked(old('require_password_change'))
                        class="mt-1 h-4 w-4 rounded border-black/30 text-accent focus:ring-accent"
                    >
                    <span>
                        <span class="font-medium text-black/90">{{ __('messages.admin_users.form.require_password_change.label') }}</span>
                        <span class="block text-xs text-black/60">{{ __('messages.admin_users.form.require_password_change.help') }}</span>
                    </span>
                </label>
            </div>

            <div>
                <span class="text-sm font-medium text-black/80">{{ __('messages.admin_users.permissions.form_title') }}</span>
                <p class="mt-1 text-xs text-black/60">{{ __('messages.admin_users.permissions.form_description') }}</p>

                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    @foreach ($permissionModules as $moduleKey => $module)
                        <label class="flex items-start gap-3 rounded border border-black/10 bg-black/2 p-4 text-sm text-black/80">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $moduleKey }}"
                                class="mt-1 h-4 w-4 rounded border-black/30 text-accent focus:ring-accent"
                                @checked(in_array($moduleKey, old('permissions', []), true))
                            >
                            <span>
                                <span class="font-medium text-black/90">{{ $module['label'] }}</span>
                                <span class="block text-xs text-black/60">{{ $module['description'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="password" class="flex items-center gap-1 text-sm font-medium text-black/80">
                        {{ __('messages.admin_users.form.password.label') }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="form-input mt-2 w-full rounded border border-black/10 bg-white px-3 py-2 text-sm text-black shadow-none focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40"
                        placeholder="{{ __('messages.admin_users.form.password.placeholder') }}"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="flex items-center gap-1 text-sm font-medium text-black/80">
                        {{ __('messages.admin_users.form.password_confirmation.label') }}
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="form-input mt-2 w-full rounded border border-black/10 bg-white px-3 py-2 text-sm text-black shadow-none focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/40"
                        placeholder="{{ __('messages.admin_users.form.password_confirmation.placeholder') }}"
                    >
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a
                    href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center justify-center rounded border border-black/20 px-4 py-2 text-sm font-semibold text-black/70 transition hover:bg-black/5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/40"
                >
                    {{ __('messages.admin_users.form.cancel_button') }}
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/70"
                >
                    {{ __('messages.admin_users.form.submit_button') }}
                </button>
            </div>
        </form>
    </div>
@endsection
