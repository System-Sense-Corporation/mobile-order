@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.admin.users.title'))

@section('page-title', __('messages.admin.users.title'))

@php
    $users = [
        ['name' => '田中 太郎', 'email' => 'tanaka@example.com', 'phone' => '080-1234-5678', 'department' => '営業部', 'authority' => __('messages.admin.users.roles.admin')],
        ['name' => '佐藤 花子', 'email' => 'sato@example.com', 'phone' => '090-8765-4321', 'department' => 'サポート部', 'authority' => __('messages.admin.users.roles.manager')],
        ['name' => 'John Smith', 'email' => 'jsmith@example.com', 'phone' => '070-5555-1111', 'department' => 'Logistics', 'authority' => __('messages.admin.users.roles.staff')],
    ];
@endphp

@section('content')
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
        <div class="mt-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-black/5 text-left text-sm">
            <thead class="bg-black/5 text-xs uppercase tracking-wide text-black/60">
                <tr>
                    <th scope="col" class="px-6 py-3 font-semibold">{{ __('messages.admin.users.table.name') }}</th>
                    <th scope="col" class="px-6 py-3 font-semibold">{{ __('messages.admin.users.table.email') }}</th>
                    <th scope="col" class="px-6 py-3 font-semibold">{{ __('messages.admin.users.table.phone') }}</th>
                    <th scope="col" class="px-6 py-3 font-semibold">{{ __('messages.admin.users.table.department') }}</th>
                    <th scope="col" class="px-6 py-3 font-semibold">{{ __('messages.admin.users.table.authority') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5 bg-white">
                @foreach ($users as $user)
                    <tr class="hover:bg-black/2">
                        <td class="px-6 py-4 font-medium text-black/90">{{ $user['name'] }}</td>
                        <td class="px-6 py-4 text-black/70">{{ $user['email'] }}</td>
                        <td class="px-6 py-4 text-black/70">{{ $user['phone'] }}</td>
                        <td class="px-6 py-4 text-black/70">{{ $user['department'] }}</td>
                        <td class="px-6 py-4 text-black/70">{{ $user['authority'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
