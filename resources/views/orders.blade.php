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
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.orders.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ __('messages.index.cards.orders.description') }}
                        </p>
                    </div>
                    <div class="flex flex-col items-stretch gap-2 sm:items-end">
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <form
                                method="POST"
                                action="{{ route('orders.email') }}"
                                class="flex flex-wrap items-center gap-2"
                            >
                                @csrf
                                <label class="sr-only" for="export-email">{{ __('messages.orders.actions.email_label') }}</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="export-email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="{{ __('messages.orders.actions.email_placeholder') }}"
                                    class="w-48 rounded-full border border-slate-300 px-3 py-1.5 text-xs text-slate-700 shadow-sm transition focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/50"
                                />
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-[#F4DADA] px-4 py-2 text-xs font-semibold text-slate-900 shadow-sm transition hover:bg-[#f0caca] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F4DADA]"
                                >
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.977 6.186a2.25 2.25 0 01.144-.546l.146-.438C4.67 3.68 5.52 3 6.489 3h7.022c.97 0 1.82.68 2.222 2.202l.146.438c.223.668.223 1.385 0 2.053l-.146.438C15.33 9.32 14.48 10 13.511 10H6.489c-.97 0-1.82-.68-2.222-2.202l-.146-.438a2.251 2.251 0 01-.144-.546z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.75 6.75l4.186 2.79a1.5 1.5 0 001.628 0L14.75 6.75" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 11.5h10" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 14h6" />
                                    </svg>
                                    <span>{{ __('messages.orders.actions.send') }}</span>
                                </button>
                            </form>
                            <a
                                href="{{ route('orders.export') }}"
                                class="inline-flex items-center gap-2 rounded-full bg-accent px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                            >
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5V16A1.5 1.5 0 005.25 17.5h9.5A1.5 1.5 0 0016.25 16v-2.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 3.25v9.5m0 0l3-3m-3 3l-3-3" />
                                </svg>
                                <span>{{ __('messages.orders.actions.download') }}</span>
                            </a>
                        </div>
                        @error('email')
                            <p class="text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
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
                <div class="overflow-x-auto">
                    <table class="orders-table min-w-full divide-y divide-slate-200">
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
                                    <td
                                        data-label="{{ __('messages.orders.table.time') }}"
                                        class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900"
                                    >
                                        {{ $createdAt }}
                                    </td>
                                    <td
                                        data-label="{{ __('messages.orders.table.customer') }}"
                                        class="max-w-xs px-6 py-4 text-sm font-medium text-slate-900"
                                    >
                                        {{ $order->customer?->name ?? $order->customer_name ?? '—' }}
                                    </td>
                                    <td data-label="{{ __('messages.orders.table.items') }}" class="px-6 py-4 text-sm">
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
                                    <td data-label="{{ __('messages.orders.table.status') }}" class="px-6 py-4 text-sm">
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
                                    <td data-label="{{ __('messages.orders.table.actions') }}" class="actions-cell px-6 py-4 text-right text-sm">
                                        <div class="actions-inline flex flex-wrap justify-end gap-2">
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

                                            <form
                                                method="POST"
                                                action="{{ route('orders.destroy', $order) }}"
                                                class="inline-flex"
                                                onsubmit="return confirm('{{ __('messages.orders.actions.confirm_delete') }}');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-2 rounded-full border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500"
                                                >
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

            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .orders-table td {
            vertical-align: top;
        }

        @media (max-width: 767.98px) {
            .orders-table {
                border-collapse: separate;
                width: 100%;
            }

            .orders-table thead {
                display: none;
            }

            .orders-table tbody {
                display: block;
            }

            .orders-table tr {
                display: block;
                border-bottom: 1px solid #e2e8f0;
            }

            .orders-table tr:last-child {
                border-bottom: none;
            }

            .orders-table td {
                display: block;
                padding: 0.75rem 1.5rem;
            }

            .orders-table td::before {
                content: attr(data-label);
                display: block;
                margin-bottom: 0.35rem;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b;
            }

            .orders-table td.actions-cell {
                padding-bottom: 1.25rem;
            }

            .orders-table td.actions-cell .actions-inline {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                align-items: stretch;
            }

            .orders-table td.actions-cell .actions-inline > a,
            .orders-table td.actions-cell .actions-inline > form {
                width: 100%;
            }

            .orders-table td.actions-cell .actions-inline > a {
                display: flex;
                justify-content: center;
            }

            .orders-table td.actions-cell .actions-inline > form > * {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

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
