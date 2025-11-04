@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.admin_users.title'))

@section('page-title', __('messages.admin_users.title'))

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-lg bg-white p-5 shadow-sm ring-1 ring-black/5 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-accent">{{ __('messages.admin_users.title') }}</h2>
                <p class="mt-1 text-sm text-black/60">{{ __('messages.admin_users.description') }}</p>
            </div>

            <div class="flex w-full flex-col gap-3 md:w-auto md:flex-row md:items-center">
                <label for="user-search" class="relative flex-1 md:w-64">
                    <span class="sr-only">{{ __('messages.admin_users.placeholders.search') }}</span>
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-black/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.9 14.32a7 7 0 1 1 1.414-1.414l3.39 3.39a1 1 0 0 1-1.414 1.414l-3.39-3.39ZM14 9a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input
                        type="search"
                        id="user-search"
                        class="form-input w-full pl-9"
                        placeholder="{{ __('messages.admin_users.placeholders.search') }}"
                    >
                </label>

                <button type="button" class="btn-secondary whitespace-nowrap">
                    {{ __('messages.admin_users.filters.permission') }}
                </button>

                <a href="{{ route('admin.users.form') }}" class="btn-primary whitespace-nowrap">
                    {{ __('messages.admin_users.actions.create') }}
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-lg bg-white shadow-sm ring-1 ring-black/5">
            <table class="min-w-full divide-y divide-black/10 text-left text-sm">
                <thead class="bg-black/5 text-xs uppercase tracking-wide text-black/60">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.no') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.user_id') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.name') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.department') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.authority') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.email') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.phone') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.status') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.last_login') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @foreach ($users as $index => $user)
                        <tr class="hover:bg-black/5">
                            <td class="px-4 py-3 font-medium text-black/70">{{ sprintf('%02d', $index + 1) }}</td>
                            <td class="px-4 py-3 font-medium text-accent/90">{{ $user['user_id'] }}</td>
                            <td class="px-4 py-3 text-black/80">{{ $user['name'] }}</td>
                            <td class="px-4 py-3 text-black/70">{{ $user['department'] }}</td>
                            <td class="px-4 py-3 text-black/70">{{ __('messages.admin_users.roles.' . $user['authority']) }}</td>
                            <td class="px-4 py-3 text-black/70">{{ $user['email'] }}</td>
                            <td class="px-4 py-3 text-black/70">{{ $user['phone'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                                    {{ __('messages.admin_users.statuses.' . $user['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-black/60">{{ $user['last_login'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <section class="space-y-5 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-accent">{{ __('messages.admin_users.permissions.matrix_title') }}</h2>
                    <p class="text-sm text-black/60">{{ __('messages.admin_users.permissions.matrix_description') }}</p>
                </div>

                <div class="flex flex-col gap-2 md:flex-row md:items-center">
                    <button
                        type="reset"
                        form="permission-matrix-form"
                        class="btn-secondary"
                    >
                        {{ __('messages.admin_users.permissions.actions.reset') }}
                    </button>

                    <button
                        type="submit"
                        form="permission-matrix-form"
                        class="btn-primary"
                    >
                        {{ __('messages.admin_users.permissions.actions.save') }}
                    </button>
                </div>
            </div>

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">{{ __('messages.admin_users.permissions.validation_error_heading') }}</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                id="permission-matrix-form"
                action="{{ route('admin.users.permissions') }}"
                method="POST"
                class="overflow-x-auto"
            >
                @csrf
                <table class="min-w-full divide-y divide-black/10 text-left text-sm">
                    <thead class="bg-black/5 text-xs uppercase tracking-wide text-black/60">
                        <tr>
                            <th scope="col" class="min-w-[200px] px-4 py-3 align-bottom">
                                {{ __('messages.admin_users.permissions.columns.user') }}
                            </th>
                            @foreach ($permissionModules as $moduleKey => $module)
                                <th scope="col" class="min-w-[160px] px-4 py-3 align-bottom">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-medium text-black/80">{{ $module['label'] }}</span>
                                        <span class="text-[11px] font-normal normal-case text-black/50">{{ $module['description'] }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5">
                        @foreach ($users as $user)
                            @php
                                $userId = $user['user_id'];
                                $oldSelection = old('permissions.' . $userId);
                                $selectedPermissions = is_array($oldSelection) ? $oldSelection : ($user['permissions'] ?? []);
                            @endphp
                            <tr class="hover:bg-black/5">
                                <th scope="row" class="px-4 py-4 align-top">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-semibold text-black/80">{{ $user['name'] }}</span>
                                        <span class="text-xs text-black/50">{{ $user['email'] }}</span>
                                        <button
                                            type="button"
                                            class="self-start text-xs font-medium text-accent hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/40"
                                            data-permission-row-toggle
                                            data-permission-target="{{ $userId }}"
                                        >
                                            {{ __('messages.admin_users.permissions.actions.toggle_row') }}
                                        </button>
                                    </div>
                                </th>
                                @foreach ($permissionModules as $moduleKey => $module)
                                    <td class="px-4 py-4">
                                        <label class="flex items-center justify-center">
                                            <input
                                                type="checkbox"
                                                name="permissions[{{ $userId }}][]"
                                                value="{{ $moduleKey }}"
                                                class="h-4 w-4 rounded border-black/30 text-accent focus:ring-accent"
                                                data-permission-input="{{ $userId }}"
                                                @checked(in_array($moduleKey, $selectedPermissions, true))
                                            >
                                            <span class="sr-only">{{ $user['name'] }} - {{ $module['label'] }}</span>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-permission-row-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const target = button.getAttribute('data-permission-target');
                    const inputs = document.querySelectorAll(`[data-permission-input="${target}"]`);

                    if (!inputs.length) {
                        return;
                    }

                    const shouldCheck = Array.from(inputs).some((input) => !input.checked);
                    inputs.forEach((input) => {
                        input.checked = shouldCheck;
                    });
                });
            });
        });
    </script>
@endpush
