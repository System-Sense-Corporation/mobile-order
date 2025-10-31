@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.mobile_order.title'))

@section('page-title', __('messages.mobile_order.title'))

@php
    $customerOptions = __('messages.mobile_order.options.customers');
    $productOptions = __('messages.mobile_order.options.products');
@endphp

@section('content')
    <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
        @if (session('status'))
            <div class="rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif
        <form class="space-y-6" method="POST" action="{{ route('orders.store') }}">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.order_date') }}</span>
                    <input
                        type="date"
                        name="order_date"
                        class="form-input"
                        value="{{ old('order_date', now()->format('Y-m-d')) }}"
                        required
                    >
                    @error('order_date')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.delivery_date') }}</span>
                    <input
                        type="date"
                        name="delivery_date"
                        class="form-input"
                        value="{{ old('delivery_date', now()->addDay()->format('Y-m-d')) }}"
                        required
                    >
                    @error('delivery_date')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">{{ __('messages.mobile_order.fields.customer') }}</span>
                <select name="customer_name" class="form-input" required>
                    @foreach ($customerOptions as $customer)
                        <option value="{{ $customer }}" @selected(old('customer_name') === $customer)>{{ $customer }}</option>
                    @endforeach
                </select>
                @error('customer_name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </label>
            <div class="grid gap-4 sm:grid-cols-3">
                <label class="form-field sm:col-span-2">
                    <span class="form-label">{{ __('messages.mobile_order.fields.product') }}</span>
                    <select name="product_name" class="form-input" required>
                        @foreach ($productOptions as $product)
                            <option value="{{ $product }}" @selected(old('product_name') === $product)>{{ $product }}</option>
                        @endforeach
                    </select>
                    @error('product_name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.quantity') }}</span>
                    <input
                        type="number"
                        name="quantity"
                        class="form-input"
                        min="1"
                        value="{{ old('quantity', 1) }}"
                        required
                    >
                    @error('quantity')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">{{ __('messages.mobile_order.fields.notes') }}</span>
                <textarea
                    rows="3"
                    name="notes"
                    class="form-input"
                    placeholder="{{ __('messages.mobile_order.placeholders.notes') }}"
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </label>
            <div class="flex justify-end gap-3">
                <button type="reset" class="btn-secondary">{{ __('messages.mobile_order.buttons.reset') }}</button>
                <button type="submit" class="btn-primary">{{ __('messages.mobile_order.buttons.submit') }}</button>
            </div>
        </form>
    </div>
@endsection
