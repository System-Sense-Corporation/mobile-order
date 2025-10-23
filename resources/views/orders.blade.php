@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.orders.title'))

@section('page-title', __('messages.orders.title'))

@php
    $orders = [
        ['time' => '06:30', 'customer' => '鮮魚酒場 波しぶき', 'item' => 'tuna', 'status' => 'pending'],
        ['time' => '07:10', 'customer' => 'レストラン 潮彩', 'item' => 'salmon', 'status' => 'preparing'],
        ['time' => '08:05', 'customer' => 'ホテル ブルーサンズ', 'item' => 'shrimp', 'status' => 'shipped'],
        ['time' => '09:20', 'customer' => '旬彩料理 こはる', 'item' => 'seabream', 'status' => 'pending'],
    ];
@endphp

@section('content')
    <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-black/10">
            <thead class="bg-black/5 text-left text-sm uppercase tracking-wide text-black/60">
                <tr>
                    <th class="px-4 py-3">{{ __('messages.orders.table.time') }}</th>
                    <th class="px-4 py-3">{{ __('messages.orders.table.customer') }}</th>
                    <th class="px-4 py-3">{{ __('messages.orders.table.items') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('messages.orders.table.status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5 text-sm">
                @foreach ($orders as $order)
                    <tr class="hover:bg-black/5">
                        <td class="px-4 py-3 font-medium">{{ $order['time'] }}</td>
                        <td class="px-4 py-3">{{ $order['customer'] }}</td>
                        <td class="px-4 py-3">{{ __('messages.orders.samples.' . $order['item']) }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                                {{ __('messages.orders.statuses.' . $order['status']) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
