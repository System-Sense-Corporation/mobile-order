@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.mobile_order.title'))

@section('page-title', __('messages.mobile_order.title'))

@section('content')
    <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
        <form class="space-y-6" method="POST" action="{{ route('mobile-order.store') }}">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="form-field">
                    <span class="form-label">{{ __('messages.mobile_order.fields.order_date') }}</span>
                    <input
                        type="date"
                        name="order_date"
                        class="form-input"
                        value="{{ old('order_date', now()->toDateString()) }}"
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
                        value="{{ old('delivery_date', now()->addDay()->toDateString()) }}"
                        required
                    >
                    @error('delivery_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">{{ __('messages.mobile_order.fields.customer') }}</span>
                <select name="customer_id" class="form-input" required>
                    <option value="" disabled {{ old('customer_id') ? '' : 'selected' }}>
                        {{ __('messages.mobile_order.placeholders.customer') }}
                    </option>
                    @forelse ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>
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
                        <option value="" disabled {{ old('product_id') ? '' : 'selected' }}>
                            {{ __('messages.mobile_order.placeholders.product') }}
                        </option>
                        @forelse ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
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
                        value="{{ old('quantity', 1) }}"
                        required
                    >
                    @error('quantity')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">{{ __('messages.mobile_order.fields.notes') }}</span>
                <textarea rows="3" name="notes" class="form-input" placeholder="{{ __('messages.mobile_order.placeholders.notes') }}">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </label>
            <div class="flex justify-end gap-3">
                <button type="reset" class="btn-secondary">{{ __('messages.mobile_order.buttons.reset') }}</button>
                <button type="submit" class="btn-primary">{{ __('messages.mobile_order.buttons.submit') }}</button>
            </div>
        </form>
    </div>
@endsection
