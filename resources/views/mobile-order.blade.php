@extends('layouts.app')

@section('title', 'Mobile Order - 受注登録')

@section('page-title', '受注登録')

@section('content')
    <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
        <form class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="form-field">
                    <span class="form-label">受注日</span>
                    <input type="date" class="form-input" value="{{ now()->format('Y-m-d') }}">
                </label>
                <label class="form-field">
                    <span class="form-label">納品希望日</span>
                    <input type="date" class="form-input" value="{{ now()->addDay()->format('Y-m-d') }}">
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">顧客</span>
                <select class="form-input">
                    <option>鮮魚酒場 波しぶき</option>
                    <option>レストラン 潮彩</option>
                    <option>ホテル ブルーサンズ</option>
                </select>
            </label>
            <div class="grid gap-4 sm:grid-cols-3">
                <label class="form-field sm:col-span-2">
                    <span class="form-label">商品</span>
                    <select class="form-input">
                        <option>本マグロ 柵 500g</option>
                        <option>サーモン フィレ 1kg</option>
                        <option>ボタンエビ 20尾</option>
                        <option>真鯛 1尾</option>
                    </select>
                </label>
                <label class="form-field">
                    <span class="form-label">数量</span>
                    <input type="number" class="form-input" min="1" value="1">
                </label>
            </div>
            <label class="form-field">
                <span class="form-label">備考</span>
                <textarea rows="3" class="form-input" placeholder="特記事項があれば入力してください"></textarea>
            </label>
            <div class="flex justify-end gap-3">
                <button type="reset" class="btn-secondary">クリア</button>
                <button type="submit" class="btn-primary">仮登録</button>
            </div>
        </form>
    </div>
@endsection
