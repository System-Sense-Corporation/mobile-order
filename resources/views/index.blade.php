@extends('layouts.app')

@section('title', 'Mobile Order - トップ')

@section('page-title', 'トップメニュー')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('mobile-order') }}" class="menu-card">
            <div class="text-3xl">📱</div>
            <div class="mt-2 text-lg font-semibold">受注登録</div>
            <p class="text-sm text-black/60">現場から素早く注文を登録します。</p>
        </a>
        <a href="{{ route('orders') }}" class="menu-card">
            <div class="text-3xl">📄</div>
            <div class="mt-2 text-lg font-semibold">当日受注一覧</div>
            <p class="text-sm text-black/60">本日の受注状況を一覧で確認できます。</p>
        </a>
        <a href="{{ route('products') }}" class="menu-card">
            <div class="text-3xl">🐟</div>
            <div class="mt-2 text-lg font-semibold">商品マスタ</div>
            <p class="text-sm text-black/60">取り扱い商品の情報を管理します。</p>
        </a>
        <a href="{{ route('customers') }}" class="menu-card">
            <div class="text-3xl">🍽</div>
            <div class="mt-2 text-lg font-semibold">顧客マスタ</div>
            <p class="text-sm text-black/60">得意先の連絡先や属性を把握します。</p>
        </a>
        <a href="{{ route('settings') }}" class="menu-card">
            <div class="text-3xl">⚙️</div>
            <div class="mt-2 text-lg font-semibold">設定</div>
            <p class="text-sm text-black/60">通知先やタイムゾーンなどを調整します。</p>
        </a>
    </div>
@endsection
