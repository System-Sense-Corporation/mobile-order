@extends('layouts.app')

@section('title', __('messages.admin.users.title'))

@section('page-title', __('messages.admin.users.page_title'))

@php
    $users = [
        ['name' => 'Akira Sato', 'email' => 'akira.sato@example.com', 'role' => 'admin', 'status' => 'active', 'last_active' => '2 hours ago'],
        ['name' => 'Mayumi Chen', 'email' => 'mayumi.chen@example.com', 'role' => 'manager', 'status' => 'active', 'last_active' => '45 minutes ago'],
        ['name' => 'Kenji Arai', 'email' => 'kenji.arai@example.com', 'role' => 'staff', 'status' => 'invited', 'last_active' => '—'],
        ['name' => 'Hana Kobayashi', 'email' => 'hana.kobayashi@example.com', 'role' => 'staff', 'status' => 'suspended', 'last_active' => '3 days ago'],
    ];
@endphp

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-1 flex-col gap-3 xl:flex-row xl:items-center xl:gap-4">
                <div class="w-full xl:w-72">
                    <label for="user-search" class="sr-only">{{ __('messages.admin.users.search_label') }}</label>
                    <input
                        type="search"
                        id="user-search"
                        name="search"
                        class="form-input w-full"
                        placeholder="{{ __('messages.admin.users.search_placeholder') }}"
                    >
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" class="btn-secondary">{{ __('messages.admin.users.filters.all') }}</button>
                    <button type="button" class="btn-secondary">{{ __('messages.admin.users.filters.admin') }}</button>
                    <button type="button" class="btn-secondary">{{ __('messages.admin.users.filters.manager') }}</button>
                    <button type="button" class="btn-secondary">{{ __('messages.admin.users.filters.staff') }}</button>
                </div>
            </div>

            <button type="button" class="btn-primary w-full lg:w-auto">
                {{ __('messages.admin.users.buttons.create') }}
            </button>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
            <table class="min-w-full divide-y divide-black/10">
                <thead class="bg-black/5 text-left text-sm uppercase tracking-wide text-black/60">
                    <tr>
                        <th class="px-4 py-3">{{ __('messages.admin.users.table.name') }}</th>
                        <th class="px-4 py-3">{{ __('messages.admin.users.table.email') }}</th>
                        <th class="px-4 py-3">{{ __('messages.admin.users.table.role') }}</th>
                        <th class="px-4 py-3">{{ __('messages.admin.users.table.status') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('messages.admin.users.table.last_active') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 text-sm">
                    @foreach ($users as $user)
                        <tr class="hover:bg-black/5">
                            <td class="px-4 py-3 font-medium text-black">{{ $user['name'] }}</td>
                            <td class="px-4 py-3 text-black/70">{{ $user['email'] }}</td>
                            <td class="px-4 py-3">{{ __('messages.admin.users.roles.' . $user['role']) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                                    {{ __('messages.admin.users.statuses.' . $user['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-black/70">{{ $user['last_active'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
