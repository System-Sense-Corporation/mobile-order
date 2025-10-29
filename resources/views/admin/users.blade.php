@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.admin_users.title'))

@section('page-title', __('messages.admin_users.title'))

@php
    $users = [
        [
            'user_id' => 'USR-1001',
            'name' => '山田 太郎',
            'department' => '営業統括部',
            'authority' => 'admin',
            'email' => 'taro.yamada@example.com',
            'phone' => '090-1234-5678',
            'status' => 'active',
            'last_login' => '2024-04-18 09:42',
        ],
        [
            'user_id' => 'USR-1002',
            'name' => '佐藤 花',
            'department' => '商品開発部',
            'authority' => 'editor',
            'email' => 'hana.sato@example.com',
            'phone' => '080-9876-5432',
            'status' => 'active',
            'last_login' => '2024-04-18 08:15',
        ],
        [
            'user_id' => 'USR-1003',
            'name' => 'Michael Chen',
            'department' => 'International Sales',
            'authority' => 'viewer',
            'email' => 'michael.chen@example.com',
            'phone' => '070-3456-7890',
            'status' => 'inactive',
            'last_login' => '2024-04-12 17:28',
        ],
        [
            'user_id' => 'USR-1004',
            'name' => '鈴木 健',
            'department' => '物流管理部',
            'authority' => 'editor',
            'email' => 'ken.suzuki@example.com',
            'phone' => '090-2468-1357',
            'status' => 'active',
            'last_login' => '2024-04-17 19:03',
        ],
        [
            'user_id' => 'USR-1005',
            'name' => 'Amanda Reyes',
            'department' => 'Customer Success',
            'authority' => 'viewer',
            'email' => 'amanda.reyes@example.com',
            'phone' => '080-1122-3344',
            'status' => 'suspended',
            'last_login' => '2024-03-29 11:52',
        ],
        [
            'user_id' => 'USR-1006',
            'name' => '高橋 さくら',
            'department' => '経理部',
            'authority' => 'admin',
            'email' => 'sakura.takahashi@example.com',
            'phone' => '070-9988-7766',
            'status' => 'active',
            'last_login' => '2024-04-18 07:55',
        ],
    ];
@endphp

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
                <button type="button" class="btn-primary whitespace-nowrap">
                    {{ __('messages.admin_users.actions.create') }}
                </button>
            </div>
        </div>

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
                            <td class="px-4 py-3 text-black/70">{{ __('messages.admin_users.authorities.' . $user['authority']) }}</td>
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
    </div>
@endsection
