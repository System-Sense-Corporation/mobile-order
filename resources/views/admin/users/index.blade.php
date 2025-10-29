@extends('layouts.app')

@section('title', __('messages.admin.users.index.title'))
@section('page-title', __('messages.admin.users.index.title'))

@section('content')
    <div class="flex items-center justify-between gap-4">
        <p class="text-sm text-muted">
            {{ __('messages.admin.users.index.description') }}
        </p>
        <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex items-center rounded bg-accent px-4 py-2 text-sm font-semibold text-white transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent/50"
        >
            {{ __('messages.admin.users.index.create_button') }}
        </a>
    </div>

    @if (session('status'))
        <div class="mt-6 rounded-md border border-success/30 bg-success/10 px-4 py-3 text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/40">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                        {{ __('messages.admin.users.index.table.name') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                        {{ __('messages.admin.users.index.table.email') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                        {{ __('messages.admin.users.index.table.role') }}
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">
                        {{ __('messages.admin.users.index.table.created_at') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border bg-white">
                @forelse ($users as $user)
                    <tr class="hover:bg-muted/10">
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-text">{{ $user->name }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-text">{{ $user->email }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-text">
                            {{ __('messages.admin.users.roles.' . $user->role) }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-text">{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-sm text-muted">
                            {{ __('messages.admin.users.index.empty') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection
