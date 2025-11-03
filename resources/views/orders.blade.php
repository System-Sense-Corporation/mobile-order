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
                @php
                    $statusLabels = [
                        \App\Models\Order::STATUS_PENDING => __('messages.orders.statuses.pending'),
                        \App\Models\Order::STATUS_PREPARING => __('messages.orders.statuses.preparing'),
                        \App\Models\Order::STATUS_SHIPPED => __('messages.orders.statuses.shipped'),
                    ];
                    $statusClasses = array_merge($statusStyles ?? [], [
                        'default' => $statusStyles['default'] ?? 'bg-slate-100 text-slate-800 ring-slate-200',
                    ]);
                    $statusClassMap = collect($statusLabels)
                        ->mapWithKeys(fn ($label, $status) => [$status => $statusClasses[$status] ?? $statusClasses['default']])
                        ->toArray();
                @endphp
                <div class="hidden overflow-x-auto md:block">
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
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ __('messages.orders.table.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white text-sm text-slate-700">
                            @foreach ($orders as $order)
                                @php
                                    $timestamp = $order->created_at ?? ($order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at) : null);
                                    $createdAt = optional($timestamp)?->timezone(config('app.timezone'))->format('H:i');
                                    $statusKey = $order->status ?? \App\Models\Order::STATUS_PENDING;
                                    $statusLabel = $statusLabels[$statusKey] ?? ucfirst($statusKey);
                                    $badgeClass = $statusClassMap[$statusKey] ?? $statusClassMap[\App\Models\Order::STATUS_PENDING];
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
                                        <form method="POST" action="{{ route('orders.status', $order) }}" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <label for="order-status-{{ $order->id }}" class="sr-only">{{ __('messages.orders.table.status') }}</label>
                                            <div class="relative">
                                                <select
                                                    id="order-status-{{ $order->id }}"
                                                    name="status"
                                                    class="{{ $badgeClass }} status-select inline-flex w-full cursor-pointer appearance-none rounded-full border border-transparent px-3 py-1 text-xs font-semibold ring-1 transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                                    data-order-status-select
                                                    data-base-class="status-select inline-flex w-full cursor-pointer appearance-none rounded-full border border-transparent px-3 py-1 text-xs font-semibold ring-1 transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                                    data-status-classes='@json($statusClassMap)'
                                                >
                                                    @foreach ($statusLabels as $statusValue => $label)
                                                        <option value="{{ $statusValue }}" @selected($statusValue === $statusKey)>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 8l4 4 4-4" />
                                                </svg>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <a
                                            href="{{ route('orders.create', ['order' => $order->id]) }}"
                                            class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                        >
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z" />
                                                <path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z" />
                                            </svg>
                                            <span>{{ __('messages.orders.actions.edit') }}</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-4 md:hidden">
                    @foreach ($orders as $order)
                        @php
                            $timestamp = $order->created_at ?? ($order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at) : null);
                            $createdAt = optional($timestamp)?->timezone(config('app.timezone'))->format('H:i');
                            $statusKey = $order->status ?? \App\Models\Order::STATUS_PENDING;
                            $statusLabel = $statusLabels[$statusKey] ?? ucfirst($statusKey);
                            $badgeClass = $statusClassMap[$statusKey] ?? $statusClassMap[\App\Models\Order::STATUS_PENDING];
                        @endphp
                        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase text-slate-500">
                                        {{ __('messages.orders.table.time') }}
                                    </p>
                                    <p class="text-base font-semibold text-slate-900">{{ $createdAt }}</p>
                                </div>
                                <form method="POST" action="{{ route('orders.status', $order) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label for="order-status-mobile-{{ $order->id }}" class="sr-only">{{ __('messages.orders.table.status') }}</label>
                                    <div class="relative">
                                        <select
                                            id="order-status-mobile-{{ $order->id }}"
                                            name="status"
                                            class="{{ $badgeClass }} status-select inline-flex w-full cursor-pointer appearance-none rounded-full border border-transparent px-3 py-1 text-xs font-semibold ring-1 transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                            data-order-status-select
                                            data-base-class="status-select inline-flex w-full cursor-pointer appearance-none rounded-full border border-transparent px-3 py-1 text-xs font-semibold ring-1 transition focus:outline-none focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                            data-status-classes='@json($statusClassMap)'
                                        >
                                            @foreach ($statusLabels as $statusValue => $label)
                                                <option value="{{ $statusValue }}" @selected($statusValue === $statusKey)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 8l4 4 4-4" />
                                        </svg>
                                    </div>
                                </form>
                            </div>

                            <div class="mt-4 space-y-3 text-sm text-slate-700">
                                <div>
                                    <p class="text-xs font-semibold uppercase text-slate-500">{{ __('messages.orders.table.customer') }}</p>
                                    <p class="mt-1 font-medium text-slate-900">{{ $order->customer?->name ?? $order->customer_name ?? '—' }}</p>
                                </div>

                                <div class="space-y-1">
                                    <p class="text-xs font-semibold uppercase text-slate-500">{{ __('messages.orders.table.items') }}</p>
                                    @if ($order->product)
                                        <p class="font-medium text-slate-900">
                                            {{ $order->product->name }} × {{ number_format($order->quantity ?? 1) }}
                                        </p>
                                    @elseif (! empty($order->items))
                                        <p class="font-medium text-slate-900">
                                            {{ $order->items }}
                                        </p>
                                    @else
                                        <p class="font-medium text-slate-900">—</p>
                                    @endif
                                    <p class="text-xs text-slate-500">
                                        {{ __('messages.orders.labels.delivery') }}:
                                        {{ optional($order->delivery_date)?->format('Y/m/d') ?? '—' }}
                                    </p>
                                    @if (! empty($order->notes))
                                        <p class="text-xs text-slate-500">
                                            {{ __('messages.orders.labels.notes') }}: {{ $order->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <a
                                    href="{{ route('orders.create', ['order' => $order->id]) }}"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                >
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z" />
                                        <path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z" />
                                    </svg>
                                    <span>{{ __('messages.orders.actions.edit') }}</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
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
        });
    </script>
@endpush
