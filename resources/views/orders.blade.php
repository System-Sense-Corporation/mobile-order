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
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.time') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.customer') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.items') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.status') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.orders.table.actions') }}</th>
                            </tr>
                            <tr class="hidden border-t border-slate-200 bg-slate-50 text-xs text-slate-500 md:table-row">
                                <th class="px-6 py-3 align-top">
                                    <div class="flex flex-col gap-2">
                                        <label class="font-medium" for="filter-received-start-desktop">{{ __('messages.orders.filters.received_at.start_label') }}</label>
                                        <input
                                            id="filter-received-start-desktop"
                                            type="date"
                                            class="w-full rounded-lg border border-slate-200 px-2 py-1 text-sm text-slate-700 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                            data-filter-start
                                        >
                                        <label class="font-medium" for="filter-received-end-desktop">{{ __('messages.orders.filters.received_at.end_label') }}</label>
                                        <input
                                            id="filter-received-end-desktop"
                                            type="date"
                                            class="w-full rounded-lg border border-slate-200 px-2 py-1 text-sm text-slate-700 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                            data-filter-end
                                        >
                                    </div>
                                </th>
                                <th class="px-6 py-3 align-top">
                                    <label class="sr-only" for="filter-customer-desktop">{{ __('messages.orders.filters.customer.title') }}</label>
                                    <input
                                        id="filter-customer-desktop"
                                        type="text"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                        placeholder="{{ __('messages.orders.filters.customer.placeholder') }}"
                                        data-filter-customer
                                    >
                                </th>
                                <th class="px-6 py-3 align-top">
                                    <label class="sr-only" for="filter-details-desktop">{{ __('messages.orders.filters.details.title') }}</label>
                                    <input
                                        id="filter-details-desktop"
                                        type="text"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                        placeholder="{{ __('messages.orders.filters.details.placeholder') }}"
                                        data-filter-details
                                    >
                                </th>
                                <th class="px-6 py-3 align-top">
                                    <fieldset class="space-y-2">
                                        <legend class="font-medium">{{ __('messages.orders.filters.status.title') }}</legend>
                                        @foreach ($statusLabels as $statusValue => $label)
                                            <label class="flex items-center gap-2">
                                                <input type="checkbox" value="{{ $statusValue }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-status>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </fieldset>
                                </th>
                                <th class="px-6 py-3 align-top text-right">
                                    <fieldset class="flex flex-col items-end gap-2">
                                        <legend class="sr-only">{{ __('messages.orders.filters.actions.title') }}</legend>
                                        <label class="flex items-center gap-2 text-slate-600">
                                            <input type="checkbox" value="status" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-action>
                                            <span>{{ __('messages.orders.filters.actions.status') }}</span>
                                        </label>
                                        <label class="flex items-center gap-2 text-slate-600">
                                            <input type="checkbox" value="edit" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-action>
                                            <span>{{ __('messages.orders.filters.actions.edit') }}</span>
                                        </label>
                                        <label class="flex items-center gap-2 text-slate-600">
                                            <input type="checkbox" value="delete" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-action>
                                            <span>{{ __('messages.orders.filters.actions.delete') }}</span>
                                        </label>
                                        <button type="button" class="mt-2 inline-flex items-center gap-2 rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100" data-filter-reset>
                                            {{ __('messages.orders.filters.reset') }}
                                        </button>
                                    </fieldset>
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
                                    $filterActions = 'status,edit,delete';
                                @endphp
                                <tr
                                    class="hover:bg-slate-50 align-top"
                                    data-order-row
                                    data-received-at="{{ $filterReceivedAt ?? '' }}"
                                    data-customer="{{ e($filterCustomer) }}"
                                    data-details="{{ e($filterDetails) }}"
                                    data-status="{{ $statusKey }}"
                                    data-actions="{{ $filterActions }}"
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
                    <div class="grid grid-cols-1 gap-3">
                        <div class="grid grid-cols-1 gap-2">
                            <label class="text-xs font-semibold text-slate-600" for="filter-received-start-mobile">{{ __('messages.orders.filters.received_at.start_label') }}</label>
                            <input
                                id="filter-received-start-mobile"
                                type="date"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                data-filter-start
                            >
                        </div>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="text-xs font-semibold text-slate-600" for="filter-received-end-mobile">{{ __('messages.orders.filters.received_at.end_label') }}</label>
                            <input
                                id="filter-received-end-mobile"
                                type="date"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                data-filter-end
                            >
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-600" for="filter-customer-mobile">{{ __('messages.orders.filters.customer.title') }}</label>
                            <input
                                id="filter-customer-mobile"
                                type="text"
                                class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                placeholder="{{ __('messages.orders.filters.customer.placeholder') }}"
                                data-filter-customer
                            >
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600" for="filter-details-mobile">{{ __('messages.orders.filters.details.title') }}</label>
                            <input
                                id="filter-details-mobile"
                                type="text"
                                class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent"
                                placeholder="{{ __('messages.orders.filters.details.placeholder') }}"
                                data-filter-details
                            >
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-600">{{ __('messages.orders.filters.status.title') }}</span>
                        <div class="mt-2 space-y-2">
                            @foreach ($statusLabels as $statusValue => $label)
                                <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                    <input type="checkbox" value="{{ $statusValue }}" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-status>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-600">{{ __('messages.orders.filters.actions.title') }}</span>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                <input type="checkbox" value="status" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-action>
                                <span>{{ __('messages.orders.filters.actions.status') }}</span>
                            </label>
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                <input type="checkbox" value="edit" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-action>
                                <span>{{ __('messages.orders.filters.actions.edit') }}</span>
                            </label>
                            <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                <input type="checkbox" value="delete" class="h-4 w-4 rounded border-slate-300 text-accent focus:ring-accent" data-filter-action>
                                <span>{{ __('messages.orders.filters.actions.delete') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100" data-filter-reset>
                            {{ __('messages.orders.filters.reset') }}
                        </button>
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
                        $filterActions = 'status,edit,delete';
                    @endphp
                    <li
                        class="p-4"
                        data-order-card
                        data-received-at="{{ $filterReceivedAt ?? '' }}"
                        data-customer="{{ e($filterCustomer) }}"
                        data-details="{{ e($filterDetails) }}"
                        data-status="{{ $statusKey }}"
                        data-actions="{{ $filterActions }}"
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
        received: { start: '', end: '' },
        customer: '',
        details: '',
        statuses: new Set(),
        actions: new Set(),
    };

    const rows = Array.from(document.querySelectorAll('[data-order-row]'));
    const cards = Array.from(document.querySelectorAll('[data-order-card]'));
    const emptyStates = Array.from(document.querySelectorAll('[data-empty-state]'));

    const matchesElement = (element) => {
        const data = element.dataset || {};
        const { start, end } = filterState.received;

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

        const customerQuery = filterState.customer.trim().toLowerCase();
        if (customerQuery) {
            const haystack = (data.customer || '').toString();
            if (!haystack.includes(customerQuery)) {
                return false;
            }
        }

        const detailQuery = filterState.details.trim().toLowerCase();
        if (detailQuery) {
            const haystack = (data.details || '').toString();
            if (!haystack.includes(detailQuery)) {
                return false;
            }
        }

        if (filterState.statuses.size > 0) {
            const status = data.status || '';
            if (!filterState.statuses.has(status)) {
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

    const syncTextInputs = (inputs, value, changed = null) => {
        inputs.forEach((input) => {
            if (input === changed) return;
            if (input.value !== value) {
                input.value = value;
            }
        });
    };

    const syncCheckboxes = (inputs, values, changed = null) => {
        inputs.forEach((input) => {
            if (input === changed) return;
            input.checked = values.has(input.value);
        });
    };

    const startInputs = Array.from(document.querySelectorAll('[data-filter-start]'));
    const endInputs = Array.from(document.querySelectorAll('[data-filter-end]'));
    const customerInputs = Array.from(document.querySelectorAll('[data-filter-customer]'));
    const detailInputs = Array.from(document.querySelectorAll('[data-filter-details]'));
    const statusInputs = Array.from(document.querySelectorAll('[data-filter-status]'));
    const actionInputs = Array.from(document.querySelectorAll('[data-filter-action]'));
    const resetButtons = Array.from(document.querySelectorAll('[data-filter-reset]'));

    startInputs.forEach((input) => {
        input.addEventListener('change', () => {
            const value = (input.value || '').trim();
            filterState.received.start = value;
            syncTextInputs(startInputs, value, input);
            applyFilters();
        });
    });

    endInputs.forEach((input) => {
        input.addEventListener('change', () => {
            const value = (input.value || '').trim();
            filterState.received.end = value;
            syncTextInputs(endInputs, value, input);
            applyFilters();
        });
    });

    customerInputs.forEach((input) => {
        input.addEventListener('input', () => {
            const value = (input.value || '').trim();
            filterState.customer = value;
            syncTextInputs(customerInputs, value, input);
            applyFilters();
        });
    });

    detailInputs.forEach((input) => {
        input.addEventListener('input', () => {
            const value = (input.value || '').trim();
            filterState.details = value;
            syncTextInputs(detailInputs, value, input);
            applyFilters();
        });
    });

    statusInputs.forEach((input) => {
        input.addEventListener('change', () => {
            if (input.checked) {
                filterState.statuses.add(input.value);
            } else {
                filterState.statuses.delete(input.value);
            }
            syncCheckboxes(statusInputs, filterState.statuses, input);
            applyFilters();
        });
    });

    actionInputs.forEach((input) => {
        input.addEventListener('change', () => {
            if (input.checked) {
                filterState.actions.add(input.value);
            } else {
                filterState.actions.delete(input.value);
            }
            syncCheckboxes(actionInputs, filterState.actions, input);
            applyFilters();
        });
    });

    const resetFilters = () => {
        filterState.received.start = '';
        filterState.received.end = '';
        filterState.customer = '';
        filterState.details = '';
        filterState.statuses.clear();
        filterState.actions.clear();

        syncTextInputs(startInputs, '');
        syncTextInputs(endInputs, '');
        syncTextInputs(customerInputs, '');
        syncTextInputs(detailInputs, '');
        syncCheckboxes(statusInputs, filterState.statuses);
        syncCheckboxes(actionInputs, filterState.actions);

        applyFilters();
    };

    resetButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            resetFilters();
        });
    });

    applyFilters();
});
</script>
@endpush
