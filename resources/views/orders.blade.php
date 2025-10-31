@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.orders.title'))

@section('page-title', __('messages.orders.title'))

@section('content')
    <div class="space-y-4">
        @if (session('success'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
            <table class="min-w-full divide-y divide-black/10">
                <thead class="bg-black/5 text-left text-sm uppercase tracking-wide text-black/60">
                    <tr>
                        <th class="px-4 py-3">{{ __('messages.orders.table.time') }}</th>
                        <th class="px-4 py-3">{{ __('messages.orders.table.customer') }}</th>
                        <th class="px-4 py-3">{{ __('messages.orders.table.items') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('messages.orders.table.status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 text-sm">
                    @forelse ($orders as $order)
                        @php
                            $statusValue = (string) ($order->status ?? '');
                            $statusKey = 'messages.orders.statuses.' . $statusValue;
                            $statusLabel = $statusValue !== '' ? __($statusKey) : null;
                            $statusText = $statusLabel && $statusLabel !== $statusKey
                                ? $statusLabel
                                : ($statusValue !== ''
                                    ? \Illuminate\Support\Str::of($statusValue)->headline()
                                    : '—');
                        @endphp
                        <tr class="hover:bg-black/5">
                            <td class="px-4 py-3 font-medium">
                                {{ $order->received_at?->timezone(config('app.timezone'))->format('H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">{{ $order->customer_name }}</td>
                            <td class="px-4 py-3">{{ $order->items }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                                    {{ $statusText }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-sm text-black/50">
                                {{ __('messages.orders.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($orders, 'hasPages') && $orders->hasPages())
            <div>
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
