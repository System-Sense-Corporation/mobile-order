@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.mobile_order.title'))

@section('page-title', __('messages.mobile_order.title'))

@php
    $customerOptions = __('messages.mobile_order.options.customers');
    $productOptions = __('messages.mobile_order.options.products');
@endphp

@section('content')
    <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
        <form method="POST" action="{{ route('mobile-order.store') }}" class="space-y-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.order_date') }}</span>
                    <input
                        type="date"
                        name="order_date"
                        class="form-input"
                        value="{{ old('order_date', now()->format('Y-m-d')) }}"
                    >
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.delivery_date') }}</span>
                    <input
                        type="date"
                        name="delivery_date"
                        class="form-input"
                        value="{{ old('delivery_date', now()->addDay()->format('Y-m-d')) }}"
                    >
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">{{ __('messages.mobile_order.fields.customer') }}</span>
                <select name="customer" class="form-input">
                    @foreach ($customerOptions as $customer)
                        <option value="{{ $customer }}" @selected(old('customer') === $customer)>
                            {{ $customer }}
                        </option>
                    @endforeach
                </select>
            </label>
            <div class="grid gap-4 sm:grid-cols-3">
                <label class="form-field sm:col-span-2">
                    <span class="form-label">{{ __('messages.mobile_order.fields.product') }}</span>
                    <select name="product" class="form-input">
                        @foreach ($productOptions as $product)
                            <option value="{{ $product }}" @selected(old('product') === $product)>
                                {{ $product }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.quantity') }}</span>
                    <input
                        type="number"
                        name="quantity"
                        class="form-input"
                        min="1"
                        value="{{ old('quantity', 1) }}"
                    >
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">{{ __('messages.mobile_order.fields.notes') }}</span>
                <textarea
                    name="notes"
                    rows="3"
                    class="form-input"
                    placeholder="{{ __('messages.mobile_order.placeholders.notes') }}"
                >{{ old('notes') }}</textarea>
            </label>
            <div class="flex justify-end gap-3">
                <button type="reset" class="btn-secondary">{{ __('messages.mobile_order.buttons.reset') }}</button>
                <button type="submit" class="btn-primary">{{ __('messages.mobile_order.buttons.submit') }}</button>
            </div>
        </form>
    </div>
@endsection
