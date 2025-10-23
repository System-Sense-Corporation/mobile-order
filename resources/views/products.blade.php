@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.products.title'))

@section('page-title', __('messages.products.title'))

@php
    $products = [
        ['code' => 'P-1001', 'name' => '本マグロ 柵 500g', 'unit' => '柵', 'price' => '4,500'],
        ['code' => 'P-1002', 'name' => 'サーモン フィレ 1kg', 'unit' => 'パック', 'price' => '3,200'],
        ['code' => 'P-1003', 'name' => 'ボタンエビ 20尾', 'unit' => 'ケース', 'price' => '5,800'],
        ['code' => 'P-1004', 'name' => '真鯛 1尾 (約1.5kg)', 'unit' => '尾', 'price' => '2,400'],
        ['code' => 'P-1005', 'name' => 'アジ 開き 10枚', 'unit' => 'セット', 'price' => '1,200'],
    ];
@endphp

@section('content')
    <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-black/10">
            <thead class="bg-black/5 text-left text-sm uppercase tracking-wide text-black/60">
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
                        <td class="px-4 py-3 font-medium">{{ $product['code'] }}</td>
                        <td class="px-4 py-3">{{ $product['name'] }}</td>
                        <td class="px-4 py-3">{{ $product['unit'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $product['price'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
