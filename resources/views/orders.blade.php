@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.orders.title'))

@section('page-title', __('messages.orders.title'))

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                <svg class="mt-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.128-.088l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.orders.title') }}</h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ __('messages.index.cards.orders.description') }}
                </p>
            </div>

            @if ($orders->isEmpty())
                <div class="px-6 py-12 text-center text-slate-500">
                    {{ __('messages.orders.empty') }}
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ __('messages.orders.table.time') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ __('messages.orders.table.customer') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ __('messages.orders.table.items') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ __('messages.orders.table.status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white text-sm text-slate-700">
                            @foreach ($orders as $order)
                                @php
                                    $timestamp = $order->created_at ?? ($order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at) : null);
                                    $createdAt = optional($timestamp)?->timezone(config('app.timezone'))->format('H:i');
                                    $statusLabels = __('messages.orders.statuses');
                                    $statusLabel = $statusLabels[$order->status] ?? ucfirst($order->status);
                                    $statusStyles = [
                                        'pending' => 'bg-amber-100 text-amber-800 ring-amber-200',
                                        'preparing' => 'bg-sky-100 text-sky-800 ring-sky-200',
                                        'shipped' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
                                    ];
                                    $badgeClass = $statusStyles[$order->status] ?? 'bg-slate-100 text-slate-800 ring-slate-200';
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">
                                        {{ $createdAt }}
                                    </td>
                                    <td class="max-w-xs px-6 py-4 text-sm font-medium text-slate-900">
                                        {{ $order->customer?->name ?? $order->customer_name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($order->product)
                                            <div class="font-medium text-slate-900">
                                                {{ $order->product->name }} × {{ number_format($order->quantity ?? 1) }}
                                            </div>
                                        @elseif (! empty($order->items))
                                            <div class="font-medium text-slate-900">
                                                {{ $order->items }}
                                            </div>
                                        @else
                                            <div class="font-medium text-slate-900">—</div>
                                        @endif
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ __('messages.orders.labels.delivery') }}:
                                            {{ optional($order->delivery_date)?->format('Y/m/d') ?? '—' }}
                                        </div>
                                        @if (! empty($order->notes))
                                            <div class="mt-1 text-xs text-slate-500">
                                                {{ __('messages.orders.labels.notes') }}: {{ $order->notes }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
