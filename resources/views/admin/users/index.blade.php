@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.admin.users.title'))

@section('page-title', __('messages.admin.users.title'))

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <p class="text-sm text-black/70 md:max-w-xl">{{ __('messages.admin.users.description') }}</p>
            <a
                href="{{ route('admin.users.create') }}"
                class="inline-flex items-center justify-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/70"
            >
                {{ __('messages.admin.users.create_button') }}
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('permissions') || $errors->has('permissions.*') || $errors->has('permissions.*.*'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">{{ __('messages.admin.users.validation_error_heading') }}</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.users.updatePermissions') }}"
            class="space-y-6"
        >
            @csrf

            <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
                <div class="border-b border-black/5 bg-black/5 px-6 py-4">
                    <h2 class="text-sm font-semibold text-black/80">{{ __('messages.admin.users.permissions_matrix.title') }}</h2>
                    <p class="mt-1 text-xs text-black/60">{{ __('messages.admin.users.permissions_matrix.description') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/5 text-left text-sm">
                        <thead class="bg-black/5 text-xs uppercase tracking-wide text-black/60">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">{{ __('messages.admin.users.permissions_matrix.table.permission') }}</th>
                                <th scope="col" class="px-6 py-3 font-semibold">{{ __('messages.admin.users.permissions_matrix.table.description') }}</th>
                                @foreach ($roles as $role)
                                    <th scope="col" class="px-4 py-3 text-center font-semibold">
                                        <span class="block text-sm font-semibold text-black/80">{{ $role->label }}</span>
                                        <span class="mt-1 block text-[11px] text-black/50">{{ $role->description }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 bg-white">
                            @foreach ($permissionGroups as $groupKey => $permissions)
                                <tr class="bg-black/5 text-xs font-semibold uppercase tracking-wide text-black/60">
                                    <td colspan="{{ 2 + $roles->count() }}" class="px-6 py-3">
                                        {{ $groupLabels[$groupKey] ?? ucfirst(str_replace('_', ' ', $groupKey)) }}
                                    </td>
                                </tr>
                                @foreach ($permissions as $permission)
                                    <tr class="hover:bg-black/2">
                                        <td class="px-6 py-4 font-medium text-black/90">
                                            {{ $permission->label }}
                                            <span class="mt-1 block text-xs font-mono uppercase text-black/40">{{ $permission->routeName }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-black/70">
                                            {{ $permission->description }}
                                        </td>
                                        @foreach ($roles as $role)
                                            <td class="px-4 py-4 text-center">
                                                <label class="inline-flex items-center justify-center">
                                                    <input
                                                        type="checkbox"
                                                        name="permissions[{{ $role->key }}][]"
                                                        value="{{ $permission->key }}"
                                                        @checked($role->allows($permission->key))
                                                        class="h-4 w-4 rounded border-black/30 text-accent focus:ring-accent"
                                                    >
                                                    <span class="sr-only">
                                                        {{ __('messages.admin.users.permissions_matrix.checkbox_label', ['role' => $role->label, 'permission' => $permission->label]) }}
                                                    </span>
                                                </label>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/70"
                >
                    {{ __('messages.admin.users.permissions_matrix.save_button') }}
                </button>
            </div>
        </form>
    </div>
@endsection
