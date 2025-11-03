@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.products.form.title'))

@section('page-title', __('messages.products.form.title'))

@section('content')
    @php
        /** @var \App\Models\Product $product */
        $product = $product ?? new \App\Models\Product();
        $codeValue = old('code', $product->code);
        $nameValue = old('name', $product->name);
        $unitValue = old('unit', $product->unit);
        $priceValue = old('price', $product->price);
    @endphp

    <div class="space-y-8">
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <p class="font-medium">{{ __('messages.products.form.validation_error') }}</p>
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5">
                <div class="border-b border-black/5 bg-black/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.products.form.title') }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ __('messages.products.form.description') }}</p>
                </div>

                <form class="space-y-6 px-6 py-6" method="POST" action="{{ route('products.store') }}">
                    @csrf
                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="form-field">
                            <span class="form-label">{{ __('messages.products.form.fields.code') }}</span>
                            <input
                                type="text"
                                name="code"
                                class="form-input"
                                placeholder="{{ __('messages.products.form.placeholders.code') }}"
                                value="{{ $codeValue }}"
                                required
                            >
                            @error('code')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="form-field md:col-span-2">
                            <span class="form-label">{{ __('messages.products.form.fields.name') }}</span>
                            <input
                                type="text"
                                name="name"
                                class="form-input"
                                placeholder="{{ __('messages.products.form.placeholders.name') }}"
                                value="{{ $nameValue }}"
                                required
                            >
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="form-field">
                            <span class="form-label">{{ __('messages.products.form.fields.unit') }}</span>
                            <input
                                type="text"
                                name="unit"
                                class="form-input"
                                placeholder="{{ __('messages.products.form.placeholders.unit') }}"
                                value="{{ $unitValue }}"
                            >
                            @error('unit')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="form-field">
                            <span class="form-label">{{ __('messages.products.form.fields.price') }}</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-slate-500">¥</span>
                                <input
                                    type="number"
                                    name="price"
                                    min="0"
                                    step="1"
                                    class="form-input pl-8"
                                    placeholder="{{ __('messages.products.form.placeholders.price') }}"
                                    value="{{ $priceValue }}"
                                    required
                                >
                            </div>
                            @error('price')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3">
                        <a href="{{ route('products') }}" class="btn-secondary">{{ __('messages.products.form.buttons.cancel') }}</a>
                        <button type="submit" class="btn-primary">{{ __('messages.products.form.buttons.save') }}</button>
                    </div>
                </form>
            </div>

            <aside class="space-y-4 rounded-xl border border-blue-100 bg-blue-50 p-6 text-sm text-blue-900">
                <h3 class="text-base font-semibold text-blue-900">{{ __('messages.products.form.sidebar.title') }}</h3>
                <p>{{ __('messages.products.form.sidebar.description') }}</p>

                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-blue-700">{{ __('messages.products.table.code') }}</dt>
                        <dd class="mt-1 text-base font-semibold">{{ $codeValue ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-blue-700">{{ __('messages.products.table.name') }}</dt>
                        <dd class="mt-1 font-medium">{{ $nameValue ?: '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-blue-700">{{ __('messages.products.table.unit') }}</span>
                        <span class="font-medium">{{ $unitValue ?: '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-blue-700">{{ __('messages.products.table.price') }}</span>
                        <span class="font-medium">{{ $priceValue !== null && $priceValue !== '' ? '¥' . number_format((int) $priceValue) : '—' }}</span>
                    </div>
                </dl>

                <p class="text-xs text-blue-700">{{ __('messages.products.form.sidebar.note') }}</p>
            </aside>
        </div>
    </div>
@endsection
