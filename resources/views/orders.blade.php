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

            // Map สถานะ -> คลาส (ถ้าไม่เจอ ใช้ default)
            $statusClassMap = collect($statusLabels)->mapWithKeys(
                fn ($label, $status) => [$status => $statusStyles[$status] ?? $statusStyles['default']]
            )->toArray();
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
                                @php
                                    $filterButtonClasses = 'rounded-full p-1 text-slate-400 transition hover:text-slate-600 focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent';
                                @endphp
                                <th class="relative px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <div class="flex items-center gap-2">
                                        <span>{{ __('messages.orders.table.time') }}</span>
                                        @php($receivedFilterId = 'orders-filter-received-at')
                                        <button
                                            type="button"
                                            class="{{ $filterButtonClasses }}"
                                            data-filter-toggle="received_at"
                                            aria-controls="{{ $receivedFilterId }}"
                                            aria-expanded="false"
                                        >
                                            <span class="sr-only">{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.time')]) }}</span>
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h14m-9 6h4m-7 6h10" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        id="{{ $receivedFilterId }}"
                                        data-filter-panel="received_at"
                                        class="absolute right-0 top-full z-20 mt-2 hidden w-64 rounded-xl border border-slate-200 bg-white p-4 text-left shadow-lg"
                                        role="dialog"
                                        aria-label="{{ __('messages.orders.filters.received_at.title') }}"
                                    >
                                        <form data-filter-form="received_at" class="space-y-3">
                                            <div class="space-y-2">
                                                <label class="block text-xs font-semibold text-slate-600" for="filter-received-start">{{ __('messages.orders.filters.received_at.start_label') }}</label>
                                                <input id="filter-received-start" type="date" name="start" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-accent">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="block text-xs font-semibold text-slate-600" for="filter-received-end">{{ __('messages.orders.filters.received_at.end_label') }}</label>
                                                <input id="filter-received-end" type="date" name="end" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-accent">
                                            </div>
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" data-filter-reset="received_at" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100">{{ __('messages.orders.filters.reset') }}</button>
                                                <button type="submit" class="rounded-full bg-accent px-3 py-1 text-xs font-semibold text-white hover:bg-accent/90">{{ __('messages.orders.filters.apply') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </th>
                                <th class="relative px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <div class="flex items-center gap-2">
                                        <span>{{ __('messages.orders.table.customer') }}</span>
                                        @php($customerFilterId = 'orders-filter-customer')
                                        <button
                                            type="button"
                                            class="{{ $filterButtonClasses }}"
                                            data-filter-toggle="customer"
                                            aria-controls="{{ $customerFilterId }}"
                                            aria-expanded="false"
                                        >
                                            <span class="sr-only">{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.customer')]) }}</span>
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h14m-9 6h4m-7 6h10" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        id="{{ $customerFilterId }}"
                                        data-filter-panel="customer"
                                        class="absolute right-0 top-full z-20 mt-2 hidden w-64 rounded-xl border border-slate-200 bg-white p-4 text-left shadow-lg"
                                        role="dialog"
                                        aria-label="{{ __('messages.orders.filters.customer.title') }}"
                                    >
                                        <form data-filter-form="customer" class="space-y-3">
                                            <div class="space-y-2">
                                                <label class="block text-xs font-semibold text-slate-600" for="filter-customer-query">{{ __('messages.orders.filters.customer.title') }}</label>
                                                <input id="filter-customer-query" type="text" name="query" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-accent" placeholder="{{ __('messages.orders.filters.customer.placeholder') }}">
                                            </div>
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" data-filter-reset="customer" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100">{{ __('messages.orders.filters.reset') }}</button>
                                                <button type="submit" class="rounded-full bg-accent px-3 py-1 text-xs font-semibold text-white hover:bg-accent/90">{{ __('messages.orders.filters.apply') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </th>
                                <th class="relative px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <div class="flex items-center gap-2">
                                        <span>{{ __('messages.orders.table.items') }}</span>
                                        @php($itemsFilterId = 'orders-filter-items')
                                        <button
                                            type="button"
                                            class="{{ $filterButtonClasses }}"
                                            data-filter-toggle="details"
                                            aria-controls="{{ $itemsFilterId }}"
                                            aria-expanded="false"
                                        >
                                            <span class="sr-only">{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.items')]) }}</span>
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h14m-9 6h4m-7 6h10" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        id="{{ $itemsFilterId }}"
                                        data-filter-panel="details"
                                        class="absolute right-0 top-full z-20 mt-2 hidden w-72 rounded-xl border border-slate-200 bg-white p-4 text-left shadow-lg"
                                        role="dialog"
                                        aria-label="{{ __('messages.orders.filters.details.title') }}"
                                    >
                                        <form data-filter-form="details" class="space-y-3">
                                            <div class="space-y-2">
                                                <label class="block text-xs font-semibold text-slate-600" for="filter-details-query">{{ __('messages.orders.filters.details.title') }}</label>
                                                <input id="filter-details-query" type="text" name="query" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-accent" placeholder="{{ __('messages.orders.filters.details.placeholder') }}">
                                            </div>
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" data-filter-reset="details" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100">{{ __('messages.orders.filters.reset') }}</button>
                                                <button type="submit" class="rounded-full bg-accent px-3 py-1 text-xs font-semibold text-white hover:bg-accent/90">{{ __('messages.orders.filters.apply') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </th>
                                <th class="relative px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <div class="flex items-center gap-2">
                                        <span>{{ __('messages.orders.table.status') }}</span>
                                        @php($statusFilterId = 'orders-filter-status')
                                        <button
                                            type="button"
                                            class="{{ $filterButtonClasses }}"
                                            data-filter-toggle="status"
                                            aria-controls="{{ $statusFilterId }}"
                                            aria-expanded="false"
                                        >
                                            <span class="sr-only">{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.status')]) }}</span>
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h14m-9 6h4m-7 6h10" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        id="{{ $statusFilterId }}"
                                        data-filter-panel="status"
                                        class="absolute right-0 top-full z-20 mt-2 hidden w-56 rounded-xl border border-slate-200 bg-white p-4 text-left shadow-lg"
                                        role="dialog"
                                        aria-label="{{ __('messages.orders.filters.status.title') }}"
                                    >
                                        <form data-filter-form="status" class="space-y-3">
                                            <fieldset class="space-y-2">
                                                <legend class="block text-xs font-semibold text-slate-600">{{ __('messages.orders.filters.status.title') }}</legend>
                                                @foreach ($statusLabels as $statusValue => $label)
                                                    <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                                        <input type="checkbox" name="statuses[]" value="{{ $statusValue }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent">
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </fieldset>
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" data-filter-reset="status" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100">{{ __('messages.orders.filters.reset') }}</button>
                                                <button type="submit" class="rounded-full bg-accent px-3 py-1 text-xs font-semibold text-white hover:bg-accent/90">{{ __('messages.orders.filters.apply') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </th>
                                <th class="relative px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <div class="flex items-center justify-end gap-2">
                                        <span>{{ __('messages.orders.table.actions') }}</span>
                                        @php($actionsFilterId = 'orders-filter-actions')
                                        <button
                                            type="button"
                                            class="{{ $filterButtonClasses }}"
                                            data-filter-toggle="actions"
                                            aria-controls="{{ $actionsFilterId }}"
                                            aria-expanded="false"
                                        >
                                            <span class="sr-only">{{ __('messages.orders.filters.toggle', ['label' => __('messages.orders.table.actions')]) }}</span>
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h14m-9 6h4m-7 6h10" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        id="{{ $actionsFilterId }}"
                                        data-filter-panel="actions"
                                        class="absolute right-0 top-full z-20 mt-2 hidden w-56 rounded-xl border border-slate-200 bg-white p-4 text-left shadow-lg"
                                        role="dialog"
                                        aria-label="{{ __('messages.orders.filters.actions.title') }}"
                                    >
                                        <form data-filter-form="actions" class="space-y-3">
                                            <fieldset class="space-y-2">
                                                <legend class="block text-xs font-semibold text-slate-600">{{ __('messages.orders.filters.actions.title') }}</legend>
                                                <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                                    <input type="checkbox" name="actions[]" value="edit" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent">
                                                    <span>{{ __('messages.orders.filters.actions.edit') }}</span>
                                                </label>
                                                <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                                    <input type="checkbox" name="actions[]" value="delete" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent">
                                                    <span>{{ __('messages.orders.filters.actions.delete') }}</span>
                                                </label>
                                            </fieldset>
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" data-filter-reset="actions" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100">{{ __('messages.orders.filters.reset') }}</button>
                                                <button type="submit" class="rounded-full bg-accent px-3 py-1 text-xs font-semibold text-white hover:bg-accent/90">{{ __('messages.orders.filters.apply') }}</button>
                                            </div>
                                        </form>
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
                                    $filterReceivedAt = optional($timestamp)?->timezone(config('app.timezone'))->toIso8601String();
                                    $filterCustomer   = mb_strtolower((string) ($order->customer?->name ?? $order->customer_name ?? ''));
                                    $filterDetailsParts = [];
                                    if ($order->product) {
                                        $filterDetailsParts[] = $order->product->name;
                                    } elseif (! empty($order->items)) {
                                        $filterDetailsParts[] = $order->items;
                                    }
                                    if (! empty($order->quantity)) {
                                        $filterDetailsParts[] = $order->quantity;
                                    }
                                    if (! empty($order->notes)) {
                                        $filterDetailsParts[] = $order->notes;
                                    }
                                    if (! empty($order->delivery_date)) {
                                        $filterDetailsParts[] = optional($order->delivery_date)?->format('Y-m-d');
                                    }
                                    $filterDetails = mb_strtolower(trim(collect($filterDetailsParts)->filter()->implode(' ')));
                                @endphp
                                <tr
                                    class="hover:bg-slate-50 align-top"
                                    data-order-row
                                    data-received-at="{{ $filterReceivedAt ?? '' }}"
                                    data-customer="{{ e($filterCustomer) }}"
                                    data-details="{{ e($filterDetails) }}"
                                    data-status="{{ $statusKey }}"
                                    data-actions="status,edit,delete"
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
                        $filterReceivedAt = optional($timestamp)?->timezone(config('app.timezone'))->toIso8601String();
                        $filterCustomer   = mb_strtolower((string) ($order->customer?->name ?? $order->customer_name ?? ''));
                        $filterDetailsParts = [];
                        if ($order->product) {
                            $filterDetailsParts[] = $order->product->name;
                        } elseif (! empty($order->items)) {
                            $filterDetailsParts[] = $order->items;
                        }
                        if (! empty($order->quantity)) {
                            $filterDetailsParts[] = $order->quantity;
                        }
                        if (! empty($order->notes)) {
                            $filterDetailsParts[] = $order->notes;
                        }
                        if (! empty($order->delivery_date)) {
                            $filterDetailsParts[] = optional($order->delivery_date)?->format('Y-m-d');
                        }
                        $filterDetails = mb_strtolower(trim(collect($filterDetailsParts)->filter()->implode(' ')));
                    @endphp
                    <li
                        class="p-4"
                        data-order-card
                        data-received-at="{{ $filterReceivedAt ?? '' }}"
                        data-customer="{{ e($filterCustomer) }}"
                        data-details="{{ e($filterDetails) }}"
                        data-status="{{ $statusKey }}"
                        data-actions="status,edit,delete"
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
        @endif

        <div class="hidden px-6 py-12 text-center text-slate-500" data-filter-empty>
            {{ __('messages.orders.filters.empty') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-order-status-select]').forEach((select) => {
        const baseClass = select.dataset.baseClass || select.className;
        let statusClasses = {};
        try { statusClasses = JSON.parse(select.dataset.statusClasses || '{}'); } catch (e) {}

        const applyClasses = () => {
            const style = statusClasses[select.value] || statusClasses.default || '';
            select.className = style ? `${baseClass} ${style}` : baseClass;
        };

        applyClasses();

        select.addEventListener('change', () => {
            applyClasses();
            const form = select.closest('form');
            if (form) form.submit();
        });
    });

    const panels = new Map();
    const toggles = new Map();

    const setToggleActive = (name, active) => {
        const toggle = toggles.get(name);
        if (!toggle) return;
        toggle.dataset.active = active ? 'true' : 'false';
        if (active) {
            toggle.classList.add('text-accent');
            toggle.classList.remove('text-slate-400');
        } else {
            toggle.classList.remove('text-accent');
            toggle.classList.add('text-slate-400');
        }
    };

    const closePanels = (except = null) => {
        panels.forEach((panel, key) => {
            if (except && key === except) {
                return;
            }
            if (!panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
            }
            panel.setAttribute('aria-hidden', 'true');
            const toggle = toggles.get(key);
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    };

    const openPanel = (name) => {
        const panel = panels.get(name);
        const toggle = toggles.get(name);
        if (!panel || !toggle) return;
        closePanels();
        panel.classList.remove('hidden');
        panel.setAttribute('aria-hidden', 'false');
        toggle.setAttribute('aria-expanded', 'true');
    };

    document.querySelectorAll('[data-filter-toggle]').forEach((button) => {
        const name = button.dataset.filterToggle;
        if (!name) return;
        toggles.set(name, button);
        const panel = document.querySelector(`[data-filter-panel="${name}"]`);
        if (!panel) return;
        panels.set(name, panel);
        panel.setAttribute('aria-hidden', 'true');
        panel.addEventListener('click', (event) => event.stopPropagation());
        button.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            const isOpen = !panel.classList.contains('hidden');
            if (isOpen) {
                closePanels();
            } else {
                openPanel(name);
            }
        });
    });

    document.addEventListener('click', () => closePanels());
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closePanels();
        }
    });

    const parseDate = (value) => {
        if (!value) return null;
        const date = new Date(value);
        return Number.isNaN(date.getTime()) ? null : date;
    };

    const parseBoundaryDate = (value, endOfDay = false) => {
        if (!value) return null;
        const suffix = endOfDay ? 'T23:59:59.999' : 'T00:00:00';
        return parseDate(value.includes('T') ? value : `${value}${suffix}`);
    };

    const filterState = {
        received_at: { start: '', end: '' },
        customer: '',
        details: '',
        status: new Set(),
        actions: new Set(),
    };

    const rows = Array.from(document.querySelectorAll('[data-order-row]'));
    const cards = Array.from(document.querySelectorAll('[data-order-card]'));
    const emptyStates = Array.from(document.querySelectorAll('[data-filter-empty]'));

    const matchesElement = (element) => {
        const data = element.dataset || {};
        const { start, end } = filterState.received_at;

        if (start || end) {
            const rowDate = parseDate(data.receivedAt);
            if (!rowDate) {
                return false;
            }

            if (start) {
                const startDate = parseBoundaryDate(start);
                if (startDate && rowDate < startDate) {
                    return false;
                }
            }

            if (end) {
                const endDate = parseBoundaryDate(end, true);
                if (endDate && rowDate > endDate) {
                    return false;
                }
            }
        }

        if (filterState.customer) {
            const haystack = (data.customer || '').toString();
            if (!haystack.includes(filterState.customer)) {
                return false;
            }
        }

        if (filterState.details) {
            const haystack = (data.details || '').toString();
            if (!haystack.includes(filterState.details)) {
                return false;
            }
        }

        if (filterState.status.size > 0) {
            const status = data.status || '';
            if (!filterState.status.has(status)) {
                return false;
            }
        }

        if (filterState.actions.size > 0) {
            const actions = (data.actions || '')
                .split(',')
                .map((value) => value.trim())
                .filter(Boolean);
            const actionSet = new Set(actions);
            for (const action of filterState.actions) {
                if (!actionSet.has(action)) {
                    return false;
                }
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

    document.querySelectorAll('[data-filter-form]').forEach((form) => {
        const key = form.dataset.filterForm;
        if (!key) return;

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (key === 'received_at') {
                const startInput = form.querySelector('input[name="start"]');
                const endInput = form.querySelector('input[name="end"]');
                filterState.received_at = {
                    start: (startInput?.value || '').trim(),
                    end: (endInput?.value || '').trim(),
                };
                setToggleActive(key, Boolean(filterState.received_at.start || filterState.received_at.end));
            } else if (key === 'customer' || key === 'details') {
                const queryInput = form.querySelector('input[name="query"]');
                const value = (queryInput?.value || '').trim().toLowerCase();
                filterState[key] = value;
                setToggleActive(key, value.length > 0);
            } else if (key === 'status') {
                const selected = Array.from(form.querySelectorAll('input[name="statuses[]"]:checked')).map((input) => input.value);
                filterState.status = new Set(selected);
                setToggleActive(key, filterState.status.size > 0);
            } else if (key === 'actions') {
                const selected = Array.from(form.querySelectorAll('input[name="actions[]"]:checked')).map((input) => input.value);
                filterState.actions = new Set(selected);
                setToggleActive(key, filterState.actions.size > 0);
            }

            applyFilters();
            closePanels();
        });

        const resetButton = form.querySelector(`[data-filter-reset="${key}"]`);
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                form.reset();

                if (key === 'received_at') {
                    filterState.received_at = { start: '', end: '' };
                } else if (key === 'customer' || key === 'details') {
                    filterState[key] = '';
                } else if (key === 'status') {
                    filterState.status = new Set();
                } else if (key === 'actions') {
                    filterState.actions = new Set();
                }

                setToggleActive(key, false);
                applyFilters();
                closePanels();
            });
        }
    });

    applyFilters();
});
</script>
@endpush

