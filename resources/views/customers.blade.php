@extends('layouts.app')

@section('title', 'Mobile Order - 顧客マスタ')

@section('page-title', '顧客マスタ')

@php
    $customers = [
        ['name' => '鮮魚酒場 波しぶき', 'contact' => '03-1234-5678', 'person' => '山田様', 'note' => '毎朝8時納品'],
        ['name' => 'レストラン 潮彩', 'contact' => '045-432-1111', 'person' => '佐藤シェフ', 'note' => '高級白身魚を希望'],
        ['name' => 'ホテル ブルーサンズ', 'contact' => '0467-222-0099', 'person' => '購買部 三浦様', 'note' => '大量注文あり'],
        ['name' => '旬彩料理 こはる', 'contact' => '03-9988-7766', 'person' => '小春店主', 'note' => '土曜は臨時休業あり'],
    ];
@endphp

@section('content')
    <div class="space-y-4">
        @foreach ($customers as $customer)
            <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-black/5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-accent">{{ $customer['name'] }}</h2>
                        <p class="text-sm text-black/60">担当: {{ $customer['person'] }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium">連絡先</p>
                        <p>{{ $customer['contact'] }}</p>
                    </div>
                </div>
                <p class="mt-3 text-sm text-black/70">{{ $customer['note'] }}</p>
            </div>
        @endforeach
    </div>
@endsection
