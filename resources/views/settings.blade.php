@extends('layouts.app')

@section('title', 'Mobile Order - 設定')

@section('page-title', '設定')

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-accent">通知設定</h2>
            <form class="space-y-4">
                <label class="form-field">
                    <span class="form-label">注文通知メール</span>
                    <input type="email" class="form-input" placeholder="orders@example.com" value="orders@example.com">
                </label>
                <label class="form-field">
                    <span class="form-label">緊急連絡メール</span>
                    <input type="email" class="form-input" placeholder="alert@example.com">
                </label>
                <label class="form-field">
                    <span class="form-label">Slack Webhook URL</span>
                    <input type="url" class="form-input" placeholder="https://hooks.slack.com/services/...">
                </label>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">保存</button>
                </div>
            </form>
        </div>
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-accent">システム設定</h2>
            <form class="space-y-4">
                <label class="form-field">
                    <span class="form-label">タイムゾーン</span>
                    <select class="form-input">
                        <option value="Asia/Tokyo">Asia/Tokyo</option>
                        <option value="Asia/Bangkok" selected>Asia/Bangkok</option>
                        <option value="Asia/Singapore">Asia/Singapore</option>
                        <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh</option>
                    </select>
                </label>
                <label class="form-field">
                    <span class="form-label">営業開始時間</span>
                    <input type="time" class="form-input" value="05:00">
                </label>
                <label class="form-field">
                    <span class="form-label">営業終了時間</span>
                    <input type="time" class="form-input" value="14:00">
                </label>
                <div class="flex justify-end">
                    <button type="submit" class="btn-secondary">下書き保存</button>
                </div>
            </form>
        </div>
    </div>
@endsection
