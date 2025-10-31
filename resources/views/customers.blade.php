@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.customers.title'))

@section('page-title', __('messages.customers.title'))

@php
    $customers = [
        ['name' => '鮮魚酒場 波しぶき', 'contact' => '03-1234-5678', 'person' => '山田様', 'note' => 'wave'],
        ['name' => 'レストラン 潮彩', 'contact' => '045-432-1111', 'person' => '佐藤シェフ', 'note' => 'shiosai'],
        ['name' => 'ホテル ブルーサンズ', 'contact' => '0467-222-0099', 'person' => '購買部 三浦様', 'note' => 'blue_sands'],
    ];
@endphp

@section('content')
    <div class="space-y-4">
        @foreach ($customers as $customer)
            <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-black/5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-accent">{{ $customer['name'] }}</h2>
                        <p class="text-sm text-black/60">{{ __('messages.customers.contact_person') }}: {{ $customer['person'] }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium">{{ __('messages.customers.contact_label') }}</p>
                        <p>{{ $customer['contact'] }}</p>
                    </div>
                </div>
                <p class="mt-3 text-sm text-black/70">{{ __('messages.customers.notes.' . $customer['note']) }}</p>
            </div>
        @endforeach
    </div>
@endsection
