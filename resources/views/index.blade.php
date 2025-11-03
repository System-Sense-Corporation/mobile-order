@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.index.title'))

@section('page-title', __('messages.index.title'))

@section('content')
    @php
        $menuItems = [
            [
                'icon' => '📱',
                'route' => 'orders.create',
                'title' => __('messages.index.cards.mobile-order.title'),
                'description' => __('messages.index.cards.mobile-order.description'),
            ],
            [
                'icon' => '🐟',
                'route' => 'products',
                'title' => __('messages.index.cards.products.title'),
                'description' => __('messages.index.cards.products.description'),
            ],
            [
                'icon' => '🍽',
                'route' => 'customers',
                'title' => __('messages.index.cards.customers.title'),
                'description' => __('messages.index.cards.customers.description'),
            ],
            [
                'icon' => '⚙️',
                'route' => 'settings',
                'title' => __('messages.index.cards.settings.title'),
                'description' => __('messages.index.cards.settings.description'),
            ],
        ];
    @endphp
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($menuItems as $item)
            <a href="{{ route($item['route']) }}" class="menu-card">
                <div class="text-3xl">{{ $item['icon'] }}</div>
                <div class="mt-2 text-lg font-semibold">{{ $item['title'] }}</div>
                <p class="text-sm text-black/60">{{ $item['description'] }}</p>
            </a>
        @endforeach
    </div>
@endsection
