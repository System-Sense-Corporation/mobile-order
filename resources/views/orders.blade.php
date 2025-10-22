@extends('layouts.app')

@section('title', 'Mobile Order - 当日受注一覧')

@section('page-title', '当日受注一覧')

@php
    $orders = [
        ['time' => '06:30', 'customer' => '鮮魚酒場 波しぶき', 'items' => '本マグロ 柵 500g × 2', 'status' => '確認中'],
        ['time' => '07:10', 'customer' => 'レストラン 潮彩', 'items' => 'サーモン フィレ 1kg × 5', 'status' => '出荷準備中'],
        ['time' => '08:05', 'customer' => 'ホテル ブルーサンズ', 'items' => 'ボタンエビ 20尾 × 3', 'status' => '出荷済'],
        ['time' => '09:20', 'customer' => '旬彩料理 こはる', 'items' => '真鯛 1尾 × 4', 'status' => '確認中'],
    ];
@endphp

@section('content')
    <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-black/10">
            <thead class="bg-black/5 text-left text-sm uppercase tracking-wide text-black/60">
                <tr>
                    <th class="px-4 py-3">受付時刻</th>
                    <th class="px-4 py-3">顧客</th>
                    <th class="px-4 py-3">注文内容</th>
                    <th class="px-4 py-3 text-right">ステータス</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5 text-sm">
                @foreach ($orders as $order)
                    <tr class="hover:bg-black/5">
                        <td class="px-4 py-3 font-medium">{{ $order['time'] }}</td>
                        <td class="px-4 py-3">{{ $order['customer'] }}</td>
                        <td class="px-4 py-3">{{ $order['items'] }}</td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                                {{ $order['status'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
