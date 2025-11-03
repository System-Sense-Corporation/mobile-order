@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.products.form.title'))

@section('page-title', __('messages.products.form.title'))

@php
    $product = [
        'code' => 'P-1001',
        'name' => '本マグロ 柵 500g',
        'unit' => '柵',
        'price' => '4,500',
    ];
@endphp

@section('content')
    <div class="space-y-8">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5">
                <div class="border-b border-black/5 bg-black/5 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.products.form.title') }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ __('messages.products.form.description') }}</p>
                </div>

                <form class="space-y-6 px-6 py-6">
                    <div class="grid gap-6 md:grid-cols-2">
                        <label class="form-field">
                            <span class="form-label">{{ __('messages.products.form.fields.code') }}</span>
                            <input
                                type="text"
                                class="form-input"
                                placeholder="{{ __('messages.products.form.placeholders.code') }}"
                                value="{{ $product['code'] }}"
                            >
                        </label>

                        <label class="form-field md:col-span-2">
                            <span class="form-label">{{ __('messages.products.form.fields.name') }}</span>
                            <input
                                type="text"
                                class="form-input"
                                placeholder="{{ __('messages.products.form.placeholders.name') }}"
                                value="{{ $product['name'] }}"
                            >
                        </label>

                        <label class="form-field">
                            <span class="form-label">{{ __('messages.products.form.fields.unit') }}</span>
                            <input
                                type="text"
                                class="form-input"
                                placeholder="{{ __('messages.products.form.placeholders.unit') }}"
                                value="{{ $product['unit'] }}"
                            >
                        </label>

                        <label class="form-field">
                            <span class="form-label">{{ __('messages.products.form.fields.price') }}</span>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-slate-500">¥</span>
                                <input
                                    type="text"
                                    class="form-input pl-8"
                                    placeholder="{{ __('messages.products.form.placeholders.price') }}"
                                    value="{{ $product['price'] }}"
                                >
                            </div>
                        </label>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3">
                        <a href="{{ route('products') }}" class="btn-secondary">{{ __('messages.products.form.buttons.cancel') }}</a>
                        <button type="button" class="btn-primary">{{ __('messages.products.form.buttons.save') }}</button>
                    </div>
                </form>
            </div>

            <aside class="space-y-4 rounded-xl border border-blue-100 bg-blue-50 p-6 text-sm text-blue-900">
                <h3 class="text-base font-semibold text-blue-900">{{ __('messages.products.form.sidebar.title') }}</h3>
                <p>{{ __('messages.products.form.sidebar.description') }}</p>

                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-blue-700">{{ __('messages.products.table.code') }}</dt>
                        <dd class="mt-1 text-base font-semibold">{{ $product['code'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-blue-700">{{ __('messages.products.table.name') }}</dt>
                        <dd class="mt-1 font-medium">{{ $product['name'] }}</dd>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-blue-700">{{ __('messages.products.table.unit') }}</span>
                        <span class="font-medium">{{ $product['unit'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-semibold text-blue-700">{{ __('messages.products.table.price') }}</span>
                        <span class="font-medium">¥{{ $product['price'] }}</span>
                    </div>
                </dl>

                <p class="text-xs text-blue-700">{{ __('messages.products.form.sidebar.note') }}</p>
            </aside>
        </div>

        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            {{ __('messages.products.demo_notice') }}
        </div>
    </div>
@endsection
