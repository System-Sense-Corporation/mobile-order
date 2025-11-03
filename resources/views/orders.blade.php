@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.orders.title'))

@section('page-title', __('messages.orders.title'))

@section('content')
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
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
                        $receivedAt = optional($order->created_at)?->timezone(config('app.timezone'));
                        $updatedAt = optional($order->updated_at)?->timezone(config('app.timezone'));
                        $orderDate = $order->order_date;
                        $deliveryDate = $order->delivery_date;
                        $notes = trim((string) $order->notes);
                        $statusKey = 'messages.orders.statuses.' . $order->status;
                        $statusLabel = __($statusKey);

                        if ($statusLabel === $statusKey) {
                            $statusLabel = \Illuminate\Support\Str::headline((string) $order->status);
                        }
                    @endphp
                    <tr class="hover:bg-black/5">
                        <td class="px-4 py-3 font-medium">
                            <div>{{ $orderDate?->format('Y-m-d') ?? '—' }}</div>
                            @if ($deliveryDate)
                                <div class="text-xs text-black/60">
                                    {{ __('messages.orders.labels.delivery') }}: {{ $deliveryDate->format('Y-m-d') }}
                                </div>
                            @endif
                            @if ($receivedAt)
                                <div class="text-xs text-black/40">{{ $receivedAt->format('H:i') }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            {{ $order->customer?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div>{{ $order->product?->name ?? '—' }}</div>
                            <div class="text-xs text-black/60">
                                × {{ number_format($order->quantity ?? 0) }}
                            </div>
                            @if ($notes !== '')
                                <div class="mt-2 text-xs text-black/60">
                                    {{ __('messages.orders.labels.notes') }}: {{ $notes }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                                {{ $statusLabel }}
                            </span>
                            @if ($updatedAt)
                                <span class="mt-1 block text-[11px] font-medium uppercase tracking-wide text-black/40">
                                    {{ $updatedAt->format('Y-m-d H:i') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-black/50">
                            {{ __('messages.orders.empty') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
