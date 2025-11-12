@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.orders.title'))
@section('page-title', __('messages.orders.title'))

@section('content')
<div class="space-y-6">

    {{-- Flash --}}
    @if (session('status'))
        <div class="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            <svg class="mt-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.128-.088l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
        {{-- Header / toolbar --}}
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.orders.title') }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ __('messages.index.cards.orders.description') }}</p>
                </div>

                @php
                    /** FALLBACKS (ถ้า AppServiceProvider ยังไม่ share มา) **/
                    $btn = $btnPalette ?? [
                        'primary'   => 'btn-primary rounded-full text-xs',
                        'secondary' => 'btn-secondary rounded-full text-xs', // (อันนี้เราจะไม่ใช้แล้ว)
                        'danger'    => 'btn-danger rounded-full text-xs',    // (อันนี้เราจะไม่ใช้แล้ว)
                        'email'     => 'rounded-full bg-[#F4DADA] px-4 py-2 text-xs font-semibold text-slate-900 hover:bg-[#f0caca] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F4DADA]',
                    ];

                    $statusStyles = $statusStyles ?? [
                        \App\Models\Order::STATUS_PENDING   => 'bg-amber-50 text-amber-700 ring-amber-200',
                        \App\Models\Order::STATUS_PREPARING => 'bg-sky-50 text-sky-700 ring-sky-200',
                        \App\Models\Order::STATUS_SHIPPED   => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                        'default'                           => 'bg-slate-100 text-slate-800 ring-slate-200',
                    ];
                @endphp

                <div class="flex flex-col items-stretch gap-2 sm:items-end">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <form method="POST" action="{{ route('orders.email') }}" class="flex flex-wrap items-center gap-2">
                            @csrf
                            <label class="sr-only" for="export-email">{{ __('messages.orders.actions.email_label') }}</label>
                            <input
                                type="email" id="export-email" name="email" value="{{ old('email') }}" required
                                placeholder="{{ __('messages.orders.actions.email_placeholder') }}"
                                class="w-56 rounded-full border border-slate-300 px-3 py-1.5 text-xs text-slate-700 shadow-sm transition focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/50"
                            />
                            <button type="submit" class="inline-flex items-center gap-2 {{ $btn['email'] }}">
                                {{-- mail icon --}}
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.977 6.186a2.25 2.25 0 01.144-.546l.146-.438C4.67 3.68 5.52 3 6.489 3h7.022c.97 0 1.82.68 2.222 2.202l.146.438c.223.668.223 1.385 0 2.053l-.146.438C15.33 9.32 14.48 10 13.511 10H6.489c-.97 0-1.82-.68-2.222-2.202l-.146-.438a2.251 2.251 0 01-.144-.546z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 6.75l4.186 2.79a1.5 1.5 0 001.628 0L14.75 6.75" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 11.5h10M5 14h6" />
                                </svg>
                                <span>{{ __('messages.orders.actions.send') }}</span>
                            </button>
                        </form>

                        <a href="{{ route('orders.export') }}" class="inline-flex items-center gap-2 {{ $btn['primary'] }}">
                            {{-- download icon --}}
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5V16A1.5 1.5 0 005.25 17.5h9.5A1.5 1.5 0 0016.25 16v-2.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 3.25v9.5m0 0l3-3m-3 3l-3-3" />
                            </svg>
                            <span>{{ __('messages.orders.actions.download') }}</span>
                        </a>
                    </div>
                    @error('email') <p class="text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        @php
            $statusLabels = [
                \App\Models\Order::STATUS_PENDING   => __('messages.orders.statuses.pending'),
                \App\Models\Order::STATUS_PREPARING => __('messages.orders.statuses.preparing'),
                \App\Models\Order::STATUS_SHIPPED   => __('messages.orders.statuses.shipped'),
            ];

            $statusClassMap = collect($statusLabels)->mapWithKeys(
                fn ($label, $status) => [$status => $statusStyles[$status] ?? $statusStyles['default']]
            )->toArray();

            $timezone = config('app.timezone');

            $receivedOptions = $orders
                ->map(function ($order) use ($timezone) {
                    $timestamp = $order->created_at ?? ($order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at) : null);
                    if (! $timestamp) {
                        return null;
                    }

                    $local = $timestamp->copy()->timezone($timezone);

                    return [
                        'value' => $local->format('Y-m-d'),
                        'label' => $local->format('Y/m/d'),
                    ];
                })
                ->filter()
                ->unique(fn ($option) => $option['value'])
                ->sortBy(fn ($option) => $option['value'])
                ->values()
                ->all();

            $customerOptions = $orders
                ->map(function ($order) {
                    $name = trim((string) ($order->customer?->name ?? $order->customer_name));

                    if ($name === '') {
                        return null;
                    }

                    return [
                        'value' => $name,
                        'label' => $name,
                    ];
                })
                ->filter()
                ->unique(fn ($option) => mb_strtolower($option['value']))
                ->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)
                ->values()
                ->all();

            $detailOptions = $orders
                ->map(function ($order) {
                    $label = null;

                    if ($order->product) {
                        $label = $order->product->name;
                    } elseif (! empty($order->items)) {
                        $label = $order->items;
                    }

                    $label = trim((string) $label);

                    if ($label === '') {
                        return null;
                    }

                    return [
                        'value' => $label,
                        'label' => $label,
                    ];
                })
                ->filter()
                ->unique(fn ($option) => mb_strtolower($option['value']))
                ->sortBy('label', SORT_NATURAL | SORT_FLAG_CASE)
                ->values()
                ->all();

            $actionOptions = [
                ['value' => 'status', 'label' => __('messages.orders.filters.actions.status')],
                ['value' => 'edit', 'label' => __('messages.orders.filters.actions.edit')],
                ['value' => 'delete', 'label' => __('messages.orders.filters.actions.delete')],
            ];

            $actionValues = array_column($actionOptions, 'value');
        @endphp

        @if ($orders->isEmpty())
            <div class="px-6 py-12 text-center text-slate-500">
                {{ __('messages.orders.empty') }}
            </div>
        @else

            {{-- ===== Desktop (table) ===== --}}
            <div class="hidden md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.time') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.customer') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.items') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.status') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.actions') }}</th>
                            </tr>
                            <tr class="border-t border-slate-200 bg-slate-50 text-xs text-slate-500 md:table-row">
                                <th class="px-6 py-3 text-left align-top">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-medium text-slate-600">{{ __('messages.orders.table.time') }}</span>
                                        <div class="relative inline-block text-left" data-filter-container>
                                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="received" aria-haspopup="true" aria-expanded="false">
                                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                                </svg>
                                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.time')]) }}</span>
                                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="received"></span>
                                            </button>
                                            <div class="absolute left-0 z-10 mt-2 hidden w-64 rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="received">
                                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.received_at.title') }}</p>
                                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                                    @forelse ($receivedOptions as $option)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" value="{{ $option['value'] }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="received">
                                                            <span>{{ $option['label'] }}</span>
                                                        </label>
                                                    @empty
                                                        <p class="text-xs text-slate-400">{{ __('messages.orders.filters.none') }}</p>
                                                    @endforelse
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs">
                                                    <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="received">{{ __('messages.orders.filters.select_all') }}</button>
                                                    <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="received">{{ __('messages.orders.filters.clear') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left align-top">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-medium text-slate-600">{{ __('messages.orders.table.customer') }}</span>
                                        <div class="relative inline-block text-left" data-filter-container>
                                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="customer" aria-haspopup="true" aria-expanded="false">
                                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                                </svg>
                                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.customer')]) }}</span>
                                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="customer"></span>
                                            </button>
                                            <div class="absolute left-0 z-10 mt-2 hidden w-64 rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="customer">
                                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.customer.title') }}</p>
                                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                                    @forelse ($customerOptions as $option)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" value="{{ $option['value'] }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="customer">
                                                            <span>{{ $option['label'] }}</span>
                                                        </label>
                                                    @empty
                                                        <p class="text-xs text-slate-400">{{ __('messages.orders.filters.none') }}</p>
                                                    @endforelse
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs">
                                                    <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="customer">{{ __('messages.orders.filters.select_all') }}</button>
                                                    <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="customer">{{ __('messages.orders.filters.clear') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left align-top">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-medium text-slate-600">{{ __('messages.orders.table.items') }}</span>
                                        <div class="relative inline-block text-left" data-filter-container>
                                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="details" aria-haspopup="true" aria-expanded="false">
                                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                                </svg>
                                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.items')]) }}</span>
                                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="details"></span>
                                            </button>
                                            <div class="absolute left-0 z-10 mt-2 hidden w-64 rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="details">
                                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.details.title') }}</p>
                                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                                    @forelse ($detailOptions as $option)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" value="{{ $option['value'] }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="details">
                                                            <span>{{ $option['label'] }}</span>
                                                        </label>
                                                    @empty
                                                        <p class="text-xs text-slate-400">{{ __('messages.orders.filters.none') }}</p>
                                                    @endforelse
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs">
                                                    <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="details">{{ __('messages.orders.filters.select_all') }}</button>
                                                    <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="details">{{ __('messages.orders.filters.clear') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left align-top">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-medium text-slate-600">{{ __('messages.orders.table.status') }}</span>
                                        <div class="relative inline-block text-left" data-filter-container>
                                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="status" aria-haspopup="true" aria-expanded="false">
                                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                                </svg>
                                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.status')]) }}</span>
                                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="status"></span>
                                            </button>
                                            <div class="absolute left-0 z-10 mt-2 hidden w-64 rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="status">
                                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.status.title') }}</p>
                                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                                    @foreach ($statusLabels as $statusValue => $label)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" value="{{ $statusValue }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="status">
                                                            <span>{{ $label }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <div class="mt-3 flex items-center justify-between text-xs">
                                                    <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="status">{{ __('messages.orders.filters.select_all') }}</button>
                                                    <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="status">{{ __('messages.orders.filters.clear') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right align-top">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <span class="font-medium text-slate-600">{{ __('messages.orders.table.actions') }}</span>
                                        <div class="relative inline-block text-left" data-filter-container>
                                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="actions" aria-haspopup="true" aria-expanded="false">
                                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                                </svg>
                                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.actions')]) }}</span>
                                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="actions"></span>
                                            </button>
                                            <div class="absolute right-0 z-10 mt-2 hidden w-64 rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="actions">
                                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.actions.title') }}</p>
                                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                                    @foreach ($actionOptions as $option)
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" value="{{ $option['value'] }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="actions">
                                                            <span>{{ $option['label'] }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <div class="mt-3 space-y-2 text-xs">
                                                    <div class="flex items-center justify-between">
                                                        <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="actions">{{ __('messages.orders.filters.select_all') }}</button>
                                                        <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="actions">{{ __('messages.orders.filters.clear') }}</button>
                                                    </div>
                                                    <button type="button" class="w-full rounded-full border border-slate-200 px-3 py-1 font-semibold text-slate-600 hover:bg-slate-100" data-filter-reset-all>{{ __('messages.orders.filters.reset') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white text-sm text-slate-700">
                            @foreach ($orders as $order)
                                @php
                                    $timestamp  = $order->created_at ?? ($order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at) : null);
                                    $createdAt  = optional($timestamp)?->timezone(config('app.timezone'))->format('H:i');
                                    $statusKey  = $order->status ?? \App\Models\Order::STATUS_PENDING;
                                    $badgeClass = $statusClassMap[$statusKey] ?? $statusStyles['default'];
                                @endphp
                                @php
                                    $filterReceivedDate = $timestamp ? $timestamp->copy()->timezone($timezone)->format('Y-m-d') : null;
                                    $filterCustomerValue = trim((string) ($order->customer?->name ?? $order->customer_name ?? ''));
                                    $detailOptionValues = collect([
                                        $order->product?->name,
                                        $order->items,
                                    ])
                                        ->map(fn ($value) => trim((string) $value))
                                        ->filter()
                                        ->values()
                                        ->all();
                                    $filterActionValues = $actionValues;
                                @endphp
                                <tr
                                    class="hover:bg-slate-50 align-top"
                                    data-order-row
                                    data-received-option="{{ $filterReceivedDate ?? '' }}"
                                    data-customer-option="{{ e($filterCustomerValue) }}"
                                    data-details-options='@json($detailOptionValues)'
                                    data-status-option="{{ $statusKey }}"
                                    data-action-options='@json($filterActionValues)'
                                >
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900 tabular-nums">{{ $createdAt }}</td>
                                    <td class="max-w-xs px-6 py-4 text-sm font-medium text-slate-900">
                                        {{ $order->customer?->name ?? $order->customer_name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($order->product)
                                            <div class="font-medium text-slate-900">
                                                {{ $order->product->name }} × {{ number_format($order->quantity ?? 1) }}
                                            </div>
                                        @elseif (! empty($order->items))
                                            <div class="font-medium text-slate-900">{{ $order->items }}</div>
                                        @else
                                            <div class="font-medium text-slate-900">—</div>
                                        @endif
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ __('messages.orders.labels.delivery') }}: {{ optional($order->delivery_date)?->format('Y/m/d') ?? '—' }}
                                        </div>
                                        @if (! empty($order->notes))
                                            <div class="mt-1 text-xs text-slate-500">
                                                {{ __('messages.orders.labels.notes') }}: {{ $order->notes }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <form method="POST" action="{{ route('orders.status', $order) }}" class="inline-flex items-center gap-2">
                                            @csrf @method('PATCH')
                                            <label for="order-status-{{ $order->id }}" class="sr-only">{{ __('messages.orders.table.status') }}</label>
                                            <div class="relative">
                                                <select
                                                    id="order-status-{{ $order->id }}" name="status"
                                                    class="{{ $badgeClass }} status-select inline-flex w-full cursor-pointer appearance-none rounded-full border border-transparent px-3 py-1 text-xs font-semibold ring-1 transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                                    data-order-status-select
                                                    data-base-class="status-select inline-flex w-full cursor-pointer appearance-none rounded-full border border-transparent px-3 py-1 text-xs font-semibold ring-1 transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                                    data-status-classes='@json($statusClassMap)'
                                                >
                                                    @foreach ($statusLabels as $statusValue => $label)
                                                        <option value="{{ $statusValue }}" @selected($statusValue === $statusKey)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 8l4 4 4-4" />
                                                </svg>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        {{-- VVVV 1. เพิ่ม items-center (เหมือนที่แก้หน้า Products) VVVV --}}
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            {{-- VVVV 2. ก๊อป class ปุ่ม "Edit" (soft) มาจากหน้า Products VVVV --}}
                                            <a href="{{ route('orders.create', ['order' => $order->id]) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                                                {{-- edit icon --}}
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z" />
                                                    <path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z" />
                                                </svg>
                                                <span>{{ __('messages.orders.actions.edit') }}</span>
                                            </a>

                                            <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline-flex" onsubmit="return confirm('{{ __('messages.orders.actions.confirm_delete') }}');">
                                                @csrf @method('DELETE')
                                                {{-- VVVV 3. ก๊อป class ปุ่ม "Delete" มาจากหน้า Products VVVV --}}
                                                <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-red-600 text-white rounded-full hover:bg-red-700">
                                                    {{-- delete icon --}}
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M8.5 3a1.5 1.5 0 00-1.415 1.028L6.382 5H4.75a.75.75 0 000 1.5h.32l.55 8.25A2.25 2.25 0 007.863 17h4.274a2.25 2.25 0 002.243-2.25l.55-8.25h.32a.75.75 0 000-1.5H13.62l-.703-1.972A1.5 1.5 0 0011.5 3h-3zm2.651 2l.427 1.197a.75.75 0 00.707.503h1.687l-.52 7.8a.75.75 0 01-.748.7H7.53a.75.75 0 01-.748-.7l-.52-7.8h1.687a.75.75 0 00.707-.503L9.272 5h1.879z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ __('messages.orders.actions.delete') }}</span>
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

            {{-- ===== Mobile filters ===== --}}
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 md:hidden">
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-slate-600">{{ __('messages.orders.table.time') }}</span>
                        <div class="relative inline-block text-left" data-filter-container>
                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="received" aria-haspopup="true" aria-expanded="false">
                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                </svg>
                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.time')]) }}</span>
                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="received"></span>
                            </button>
                            <div class="absolute left-0 z-10 mt-2 hidden w-64 max-w-[calc(100vw-3rem)] rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="received">
                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.received_at.title') }}</p>
                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                    @forelse ($receivedOptions as $option)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" value="{{ $option['value'] }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="received">
                                            <span>{{ $option['label'] }}</span>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-400">{{ __('messages.orders.filters.none') }}</p>
                                    @endforelse
                                </div>
                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="received">{{ __('messages.orders.filters.select_all') }}</button>
                                    <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="received">{{ __('messages.orders.filters.clear') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-slate-600">{{ __('messages.orders.table.customer') }}</span>
                        <div class="relative inline-block text-left" data-filter-container>
                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="customer" aria-haspopup="true" aria-expanded="false">
                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                </svg>
                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.customer')]) }}</span>
                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="customer"></span>
                            </button>
                            <div class="absolute left-0 z-10 mt-2 hidden w-64 max-w-[calc(100vw-3rem)] rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="customer">
                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.customer.title') }}</p>
                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                    @forelse ($customerOptions as $option)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" value="{{ $option['value'] }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="customer">
                                            <span>{{ $option['label'] }}</span>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-400">{{ __('messages.orders.filters.none') }}</p>
                                    @endforelse
                                </div>
                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="customer">{{ __('messages.orders.filters.select_all') }}</button>
                                    <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="customer">{{ __('messages.orders.filters.clear') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-slate-600">{{ __('messages.orders.table.items') }}</span>
                        <div class="relative inline-block text-left" data-filter-container>
                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="details" aria-haspopup="true" aria-expanded="false">
                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                </svg>
                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.items')]) }}</span>
                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="details"></span>
                            </button>
                            <div class="absolute left-0 z-10 mt-2 hidden w-64 max-w-[calc(100vw-3rem)] rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="details">
                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.details.title') }}</p>
                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                    @forelse ($detailOptions as $option)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" value="{{ $option['value'] }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="details">
                                            <span>{{ $option['label'] }}</span>
                                        </label>
                                    @empty
                                        <p class="text-xs text-slate-400">{{ __('messages.orders.filters.none') }}</p>
                                    @endforelse
                                </div>
                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="details">{{ __('messages.orders.filters.select_all') }}</button>
                                    <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="details">{{ __('messages.orders.filters.clear') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-slate-600">{{ __('messages.orders.table.status') }}</span>
                        <div class="relative inline-block text-left" data-filter-container>
                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="status" aria-haspopup="true" aria-expanded="false">
                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                </svg>
                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.status')]) }}</span>
                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="status"></span>
                            </button>
                            <div class="absolute left-0 z-10 mt-2 hidden w-64 max-w-[calc(100vw-3rem)] rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="status">
                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.status.title') }}</p>
                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                    @foreach ($statusLabels as $statusValue => $label)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" value="{{ $statusValue }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="status">
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="mt-3 flex items-center justify-between text-xs">
                                    <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="status">{{ __('messages.orders.filters.select_all') }}</button>
                                    <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="status">{{ __('messages.orders.filters.clear') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-slate-600">{{ __('messages.orders.table.actions') }}</span>
                        <div class="relative inline-block text-left" data-filter-container>
                            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" data-filter-toggle="actions" aria-haspopup="true" aria-expanded="false">
                                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.25h14M6.75 10h6.5M8.25 14.75h3.5" />
                                </svg>
                                <span>{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.actions')]) }}</span>
                                <span class="hidden text-xs font-semibold text-accent" data-filter-count="actions"></span>
                            </button>
                            <div class="absolute left-0 z-10 mt-2 hidden w-64 max-w-[calc(100vw-3rem)] rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-lg" data-filter-menu="actions">
                                <p class="text-xs font-semibold text-slate-500">{{ __('messages.orders.filters.actions.title') }}</p>
                                <div class="mt-2 max-h-48 space-y-2 overflow-y-auto">
                                    @foreach ($actionOptions as $option)
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" value="{{ $option['value'] }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-checkbox data-filter-field="actions">
                                            <span>{{ $option['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="mt-3 space-y-2 text-xs">
                                    <div class="flex items-center justify-between">
                                        <button type="button" class="font-semibold text-accent hover:underline" data-filter-select-all="actions">{{ __('messages.orders.filters.select_all') }}</button>
                                        <button type="button" class="text-slate-500 hover:underline" data-filter-clear-group="actions">{{ __('messages.orders.filters.clear') }}</button>
                                    </div>
                                    <button type="button" class="w-full rounded-full border border-slate-200 px-3 py-1 font-semibold text-slate-600 hover:bg-slate-100" data-filter-reset-all>{{ __('messages.orders.filters.reset') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ===== Mobile (cards) ===== --}}
            <ul class="divide-y divide-slate-100 md:hidden">
                @foreach ($orders as $order)
                    @php
                        $timestamp  = $order->created_at ?? ($order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at) : null);
                        $createdAt  = optional($timestamp)?->timezone(config('app.timezone'))->format('H:i');
                        $statusKey  = $order->status ?? \App\Models\Order::STATUS_PENDING;
                        $badgeClass = $statusClassMap[$statusKey] ?? $statusStyles['default'];
                    @endphp
                    @php
                        $filterReceivedDate = $timestamp ? $timestamp->copy()->timezone($timezone)->format('Y-m-d') : null;
                        $filterCustomerValue = trim((string) ($order->customer?->name ?? $order->customer_name ?? ''));
                        $detailOptionValues = collect([
                            $order->product?->name,
                            $order->items,
                        ])
                            ->map(fn ($value) => trim((string) $value))
                            ->filter()
                            ->values()
                            ->all();
                        $filterActionValues = $actionValues;
                    @endphp
                    <li
                        class="p-4"
                        data-order-card
                        data-received-option="{{ $filterReceivedDate ?? '' }}"
                        data-customer-option="{{ e($filterCustomerValue) }}"
                        data-details-options='@json($detailOptionValues)'
                        data-status-option="{{ $statusKey }}"
                        data-action-options='@json($filterActionValues)'
                    >
                        <div class="rounded-2xl ring-1 ring-slate-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs text-slate-500 tabular-nums">{{ $createdAt }}</div>
                                    <div class="mt-0.5 font-medium text-slate-900">
                                        {{ $order->customer?->name ?? $order->customer_name ?? '—' }}
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('orders.status', $order) }}" class="shrink-0 inline-flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <label for="order-status-sm-{{ $order->id }}" class="sr-only">{{ __('messages.orders.table.status') }}</label>
                                    <div class="relative">
                                        <select
                                            id="order-status-sm-{{ $order->id }}" name="status"
                                            class="{{ $badgeClass }} status-select inline-flex w-full cursor-pointer appearance-none rounded-full border border-transparent px-3 py-1 text-xs font-semibold ring-1 transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                            data-order-status-select
                                            data-base-class="status-select inline-flex w-full cursor-pointer appearance-none rounded-full border border-transparent px-3 py-1 text-xs font-semibold ring-1 transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                            data-status-classes='@json($statusClassMap)'
                                        >
                                            @foreach ($statusLabels as $statusValue => $label)
                                                <option value="{{ $statusValue }}" @selected($statusValue === $statusKey)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 8l4 4 4-4" />
                                        </svg>
                                    </div>
                                </form>
                            </div>

                            <div class="mt-3 text-sm text-slate-700">
                                @if ($order->product)
                                    <div class="font-medium text-slate-900">
                                        {{ $order->product->name }} × {{ number_format($order->quantity ?? 1) }}
                                    </div>
                                @elseif (! empty($order->items))
                                    <div class="font-medium text-slate-900">{{ $order->items }}</div>
                                @else
                                    <div class="font-medium text-slate-900">—</div>
                                @endif
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ __('messages.orders.labels.delivery') }}: {{ optional($order->delivery_date)?->format('Y/m/d') ?? '—' }}
                                </div>
                                @if (! empty($order->notes))
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ __('messages.orders.labels.notes') }}: {{ $order->notes }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-3 flex items-center justify-end gap-2">
                                {{-- VVVV 4. ก๊อป class ปุ่ม "Edit" (soft) มาจากหน้า Products VVVV --}}
                                <a href="{{ route('orders.create', ['order' => $order->id]) }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent">
                                    {{-- edit icon --}}
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z" />
                                        <path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z" />
                                    </svg>
                                    <span>{{ __('messages.orders.actions.edit') }}</span>
                                </a>
                                <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline-flex"
                                      onsubmit="return confirm('{{ __('messages.orders.actions.confirm_delete') }}');">
                                    @csrf @method('DELETE')
                                    {{-- VVVV 5. ก๊อป class ปุ่ม "Delete" มาจากหน้า Products VVVV --}}
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-red-600 text-white rounded-full hover:bg-red-700">
                                        {{-- delete icon --}}
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M8.5 3a1.5 1.5 0 00-1.415 1.028L6.382 5H4.75a.75.75 0 000 1.5h.32l.55 8.25A2.25 2.25 0 007.863 17h4.274a2.25 2.25 0 002.243-2.25l.55-8.25h.32a.75.75 0 000-1.5H13.62l-.703-1.972A1.5 1.5 0 0011.5 3h-3zm2.651 2l.427 1.197a.75.75 0 00.707.503h1.687l-.52 7.8a.75.75 0 01-.748.7H7.53a.75.75 0 01-.748-.7l-.52-7.8h1.687a.75.75 0 00.707-.503L9.272 5h1.879z" clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ __('messages.orders.actions.delete') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="hidden px-6 py-12 text-center text-slate-500" data-empty-state>
                {{ __('messages.orders.filters.empty') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-order-status-select]').forEach((select) => {
        const baseClass = select.dataset.baseClass || select.className;
        let statusClasses = {};
        try {
            statusClasses = JSON.parse(select.dataset.statusClasses || '{}');
        } catch (error) {
            statusClasses = {};
        }

        const applyClasses = () => {
            const style = statusClasses[select.value] || statusClasses.default || '';
            select.className = style ? `${baseClass} ${style}` : baseClass;
        };

        applyClasses();

        select.addEventListener('change', () => {
            applyClasses();
            const form = select.closest('form');
            if (form) {
                form.submit();
            }
        });
    });

    const normalize = (value) => (value ?? '').toString().trim();

    const filterGroups = ['received', 'customer', 'details', 'status', 'actions'];
    const filterState = {};
    const groupInputs = {};

    filterGroups.forEach((group) => {
        filterState[group] = new Set();
        groupInputs[group] = [];
    });

    const rows = Array.from(document.querySelectorAll('[data-order-row]'));
    const cards = Array.from(document.querySelectorAll('[data-order-card]'));
    const emptyStates = Array.from(document.querySelectorAll('[data-empty-state]'));

    const parseList = (raw) => {
        if (!raw) {
            return [];
        }

        try {
            const parsed = JSON.parse(raw);
            if (Array.isArray(parsed)) {
                return parsed.map((item) => normalize(item)).filter(Boolean);
            }
        } catch (error) {
            // fall through to string parsing
        }

        return normalize(raw)
            .split(',')
            .map((item) => normalize(item))
            .filter(Boolean);
    };

    const matchesElement = (element) => {
        const dataset = element.dataset || {};

        if (filterState.received.size > 0) {
            const value = normalize(dataset.receivedOption);
            if (!value || !filterState.received.has(value)) {
                return false;
            }
        }

        if (filterState.customer.size > 0) {
            const value = normalize(dataset.customerOption);
            if (!value || !filterState.customer.has(value)) {
                return false;
            }
        }

        if (filterState.details.size > 0) {
            const values = parseList(dataset.detailsOptions);
            const hasMatch = values.some((value) => filterState.details.has(value));
            if (!hasMatch) {
                return false;
            }
        }

        if (filterState.status.size > 0) {
            const value = normalize(dataset.statusOption);
            if (!value || !filterState.status.has(value)) {
                return false;
            }
        }

        if (filterState.actions.size > 0) {
            const values = parseList(dataset.actionOptions);
            const matchesAll = Array.from(filterState.actions).every((value) => values.includes(value));
            if (!matchesAll) {
                return false;
            }
        }

        return true;
    };

    const applyFilters = () => {
        let anyVisibleRow = false;
        let anyVisibleCard = false;

        rows.forEach((row) => {
            const visible = matchesElement(row);
            row.classList.toggle('hidden', !visible);
            if (visible) {
                anyVisibleRow = true;
            }
        });

        cards.forEach((card) => {
            const visible = matchesElement(card);
            card.classList.toggle('hidden', !visible);
            if (visible) {
                anyVisibleCard = true;
            }
        });

        const hasVisible = anyVisibleRow || anyVisibleCard;
        emptyStates.forEach((state) => {
            state.classList.toggle('hidden', hasVisible);
        });
    };

    const updateCounts = (group) => {
        const count = filterState[group]?.size ?? 0;
        document.querySelectorAll(`[data-filter-count="${group}"]`).forEach((element) => {
            if (count > 0) {
                element.textContent = `(${count})`;
                element.classList.remove('hidden');
            } else {
                element.textContent = '';
                element.classList.add('hidden');
            }
        });
    };

    const syncGroup = (group) => {
        const selected = filterState[group];
        groupInputs[group].forEach((input) => {
            const value = normalize(input.value);
            if (!value) {
                input.checked = false;
                return;
            }
            const shouldCheck = selected.has(value);
            if (input.checked !== shouldCheck) {
                input.checked = shouldCheck;
            }
        });
    };

    document.querySelectorAll('[data-filter-checkbox]').forEach((checkbox) => {
        const group = checkbox.dataset.filterField;
        if (!filterState[group]) {
            return;
        }

        groupInputs[group].push(checkbox);

        checkbox.addEventListener('change', () => {
            const value = normalize(checkbox.value);
            if (!value) {
                checkbox.checked = false;
                return;
            }

            if (checkbox.checked) {
                filterState[group].add(value);
            } else {
                filterState[group].delete(value);
            }

            syncGroup(group);
            updateCounts(group);
            applyFilters();
        });
    });

    document.querySelectorAll('[data-filter-select-all]').forEach((button) => {
        const group = button.dataset.filterSelectAll;
        if (!filterState[group]) {
            return;
        }

        button.addEventListener('click', (event) => {
            event.preventDefault();
            groupInputs[group].forEach((checkbox) => {
                const value = normalize(checkbox.value);
                if (!value) {
                    checkbox.checked = false;
                    return;
                }
                checkbox.checked = true;
                filterState[group].add(value);
            });
            syncGroup(group);
            updateCounts(group);
            applyFilters();
        });
    });

    document.querySelectorAll('[data-filter-clear-group]').forEach((button) => {
        const group = button.dataset.filterClearGroup;
        if (!filterState[group]) {
            return;
        }

        button.addEventListener('click', (event) => {
            event.preventDefault();
            filterState[group].clear();
            syncGroup(group);
            updateCounts(group);
            applyFilters();
        });
    });

    const containerMeta = Array.from(document.querySelectorAll('[data-filter-container]'))
        .map((container) => {
            const button = container.querySelector('[data-filter-toggle]');
            const menu = container.querySelector('[data-filter-menu]');
            if (!button || !menu) {
                return null;
            }
            return { container, button, menu };
        })
        .filter(Boolean);

    const closeAllMenus = (exception = null) => {
        containerMeta.forEach(({ button, menu }) => {
            if (menu === exception) {
                return;
            }
            menu.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        });
    };

    const resetAll = () => {
        filterGroups.forEach((group) => {
            filterState[group].clear();
            syncGroup(group);
            updateCounts(group);
        });
        applyFilters();
        closeAllMenus();
    };

    document.querySelectorAll('[data-filter-reset-all]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            resetAll();
        });
    });

    containerMeta.forEach(({ button, menu }) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const isOpen = !menu.classList.contains('hidden');
            if (isOpen) {
                menu.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            } else {
                closeAllMenus(menu);
                menu.classList.remove('hidden');
                button.setAttribute('aria-expanded', 'true');
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (containerMeta.some(({ container }) => container.contains(event.target))) {
            return;
        }
        closeAllMenus();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAllMenus();
        }
    });

    filterGroups.forEach(updateCounts);
    applyFilters();
});
</script>

@endpush
