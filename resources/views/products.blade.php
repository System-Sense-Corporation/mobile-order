@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.products.title'))

@section('page-title', __('messages.products.title'))

@php
    $products = [
        ['code' => 'P-1001', 'name' => '本マグロ 柵 500g', 'unit' => '柵', 'price' => '4,500'],
        ['code' => 'P-1002', 'name' => 'サーモンフィレ 1kg', 'unit' => 'パック', 'price' => '3,200'],
        ['code' => 'P-1003', 'name' => 'ボタンエビ 20尾', 'unit' => 'ケース', 'price' => '5,800'],
        ['code' => 'P-1004', 'name' => '真鯛 1尾 (約1.5kg)', 'unit' => '尾', 'price' => '2,400'],
        ['code' => 'P-1005', 'name' => 'アジ 開き 10枚', 'unit' => 'セット', 'price' => '1,200'],
    ];
@endphp

@section('content')
    <div class="space-y-6">
        <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
            <div class="border-b border-black/5 bg-black/5 px-6 py-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.products.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-600">{{ __('messages.products.description') }}</p>
                    </div>
                    <a href="{{ route('products.form') }}" class="btn-primary">
                        {{ __('messages.products.actions.create') }}
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/10">
                    <thead class="bg-white text-left text-xs font-semibold uppercase tracking-wide text-black/60">
                        <tr>
                            <th class="px-4 py-3">{{ __('messages.products.table.code') }}</th>
                            <th class="px-4 py-3">{{ __('messages.products.table.name') }}</th>
                            <th class="px-4 py-3">{{ __('messages.products.table.unit') }}</th>
                            <th class="px-4 py-3 text-right">{{ __('messages.products.table.price') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 text-sm">
                        @foreach ($products as $product)
                            <tr class="hover:bg-black/5">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $product['code'] }}</td>
                                <td class="px-4 py-3 text-slate-800">{{ $product['name'] }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $product['unit'] }}</td>
                                <td class="px-4 py-3 text-right text-slate-900">{{ $product['price'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            {{ __('messages.products.demo_notice') }}
        </div>
    </div>
@endsection
