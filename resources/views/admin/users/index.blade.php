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

                {{-- เปลี่ยน route ที่นี่ --}}
                <a href="{{ route('admin.users.create') }}" class="btn-primary whitespace-nowrap">
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
                        <th scope="col" class="px-4 py-3 text-right">{{ __('messages.admin_users.table.actions') }}</th>
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
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <a
                                        href="{{ route('admin.users.edit', $user['user_id']) }}"
                                        class="inline-flex items-center justify-center rounded border border-accent/40 px-3 py-1 text-xs font-semibold text-accent transition hover:bg-accent/5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/40"
                                    >
                                        {{ __('messages.admin_users.actions.edit') }}
                                    </a>

                                    <form
                                        action="{{ route('admin.users.destroy', $user['user_id']) }}"
                                        method="POST"
                                        class="inline-flex"
                                        onsubmit="return confirm('{{ __('messages.admin_users.confirm_delete', ['name' => $user['name']]) }}');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center rounded border border-red-300 px-3 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-300"
                                        >
                                            {{ __('messages.admin_users.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
