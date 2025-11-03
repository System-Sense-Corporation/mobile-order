@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.mobile_order.title'))

@section('page-title', __('messages.mobile_order.title'))

@section('content')
    @php
        $customersAreDemo = $customersAreDemo ?? false;
        $productsAreDemo = $productsAreDemo ?? false;
        $editingOrder = $order ?? null;
        $isEditing = $editingOrder !== null;
    @endphp
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif
    <div
        class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5"
        data-mobile-order-card
        data-customers-demo="{{ $customersAreDemo ? 'true' : 'false' }}"
        data-products-demo="{{ $productsAreDemo ? 'true' : 'false' }}"
    >
        <form
            class="space-y-6"
            method="POST"
            action="{{ $isEditing ? route('orders.update', $editingOrder) : route('orders.store') }}"
        >
            @csrf
            @if ($isEditing)
                @method('PUT')
            @endif
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.order_date') }}</span>
                    <input
                        type="date"
                        name="order_date"
                        class="form-input"
                        value="{{ old('order_date', optional($editingOrder?->order_date)->format('Y-m-d') ?? now()->toDateString()) }}"
                        required
                    >
                    @error('order_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.delivery_date') }}</span>
                    <input
                        type="date"
                        name="delivery_date"
                        class="form-input"
                        value="{{ old('delivery_date', optional($editingOrder?->delivery_date)->format('Y-m-d') ?? now()->addDay()->toDateString()) }}"
                        required
                    >
                    @error('delivery_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">{{ __('messages.mobile_order.fields.customer') }}</span>
                <select
                    name="customer_id"
                    class="form-input"
                    required
                    data-customer-select
                    data-source="{{ route('customers') }}"
                    data-empty-text="{{ __('messages.mobile_order.empty.customers') }}"
                >
                    @php($selectedCustomer = old('customer_id', $editingOrder?->customer_id))
                    <option value="" disabled {{ $selectedCustomer ? '' : 'selected' }}>
                        {{ __('messages.mobile_order.placeholders.customer') }}
                    </option>
                    @forelse ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected($selectedCustomer == $customer->id)>
                            {{ $customer->name }}
                        </option>
                    @empty
                        <option value="" disabled>{{ __('messages.mobile_order.empty.customers') }}</option>
                    @endforelse
                </select>
                @error('customer_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </label>
            <div class="grid gap-4 sm:grid-cols-3">
                <label class="form-field sm:col-span-2">
                    <span class="form-label">{{ __('messages.mobile_order.fields.product') }}</span>
                    <select name="product_id" class="form-input" required>
                        @php($selectedProduct = old('product_id', $editingOrder?->product_id))
                        <option value="" disabled {{ $selectedProduct ? '' : 'selected' }}>
                            {{ __('messages.mobile_order.placeholders.product') }}
                        </option>
                        @forelse ($products as $product)
                            <option value="{{ $product->id }}" @selected($selectedProduct == $product->id)>
                                {{ $product->name }}
                            </option>
                        @empty
                            <option value="" disabled>{{ __('messages.mobile_order.empty.products') }}</option>
                        @endforelse
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.quantity') }}</span>
                    <input
                        type="number"
                        name="quantity"
                        class="form-input"
                        min="1"
                        value="{{ old('quantity', $editingOrder?->quantity ?? 1) }}"
                        required
                    >
                    @error('quantity')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">{{ __('messages.mobile_order.fields.notes') }}</span>
                <textarea rows="3" name="notes" class="form-input" placeholder="{{ __('messages.mobile_order.placeholders.notes') }}">{{ old('notes', $editingOrder?->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </label>
            <div class="flex justify-end gap-3">
                <button type="reset" class="btn-secondary">{{ __('messages.mobile_order.buttons.reset') }}</button>
                <button type="submit" class="btn-primary">
                    {{ $isEditing ? __('messages.mobile_order.buttons.update') : __('messages.mobile_order.buttons.submit') }}
                </button>
            </div>
        </form>
        @if ($customersAreDemo || $productsAreDemo)
            <p class="mt-4 text-xs text-black/50" data-demo-notice>
                {{ __('messages.mobile_order.demo_notice') }}
            </p>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('[data-mobile-order-card]');

            if (!container) {
                return;
            }

            const select = container.querySelector('[data-customer-select]');

            if (!select) {
                return;
            }

            const source = select.dataset.source;
            const emptyText = select.dataset.emptyText || '';
            const demoNotice = container.querySelector('[data-demo-notice]');
            const productsDemo = container.dataset.productsDemo === 'true';
            const placeholderOption = select.querySelector('option[value=""]');
            const placeholderTemplate = placeholderOption ? placeholderOption.cloneNode(true) : null;

            const updateNotice = (customersDemo) => {
                container.dataset.customersDemo = customersDemo ? 'true' : 'false';

                if (!demoNotice) {
                    return;
                }

                const shouldShow = customersDemo || productsDemo;
                demoNotice.classList.toggle('hidden', !shouldShow);
            };

            updateNotice(container.dataset.customersDemo === 'true');

            if (!source) {
                return;
            }

            const renderOptions = (customers) => {
                const previousValue = select.value;
                const normalizedCustomers = Array.isArray(customers) ? customers : [];
                const previousExists = normalizedCustomers.some((customer) => String(customer.id) === String(previousValue));

                select.innerHTML = '';

                if (normalizedCustomers.length === 0) {
                    if (placeholderTemplate) {
                        const emptyPlaceholder = placeholderTemplate.cloneNode(true);
                        emptyPlaceholder.textContent = emptyText;
                        emptyPlaceholder.disabled = true;
                        emptyPlaceholder.selected = true;
                        select.appendChild(emptyPlaceholder);
                    } else {
                        const emptyOption = document.createElement('option');
                        emptyOption.value = '';
                        emptyOption.disabled = true;
                        emptyOption.selected = true;
                        emptyOption.textContent = emptyText;
                        select.appendChild(emptyOption);
                    }

                    select.disabled = true;

                    return;
                }

                if (placeholderTemplate) {
                    const placeholderClone = placeholderTemplate.cloneNode(true);
                    placeholderClone.selected = !previousValue || !previousExists;
                    select.appendChild(placeholderClone);
                }

                normalizedCustomers.forEach((customer) => {
                    const option = document.createElement('option');
                    option.value = String(customer.id);
                    option.textContent = customer.name;

                    if (previousExists && String(customer.id) === String(previousValue)) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });

                if (!previousExists && previousValue && !placeholderTemplate && select.options.length > 0) {
                    select.options[0].selected = true;
                }

                select.disabled = false;
            };

            let isFetching = false;

            const refreshCustomers = () => {
                if (!source || isFetching) {
                    return;
                }

                isFetching = true;

                fetch(source, {
                    method: 'GET',
                    headers: {
                        Accept: 'application/json',
                    },
                    credentials: 'same-origin',
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Request failed');
                        }

                        return response.json();
                    })
                    .then((payload) => {
                        renderOptions(payload.data || []);
                        updateNotice(Boolean(payload.demo));
                    })
                    .catch(() => {
                        // silently ignore refresh errors to avoid interrupting the form
                    })
                    .finally(() => {
                        isFetching = false;
                    });
            };

            refreshCustomers();

            const intervalId = window.setInterval(refreshCustomers, 30000);

            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    refreshCustomers();
                }
            });

            window.addEventListener('beforeunload', () => {
                window.clearInterval(intervalId);
            });
        });
    </script>
@endpush
