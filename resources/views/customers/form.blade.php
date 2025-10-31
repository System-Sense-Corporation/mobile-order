@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.customers.form.title'))

@section('page-title', __('messages.customers.form.title'))

@php
    $customer = [
        'name' => '鮮魚酒場 波しぶき',
        'contact' => '03-1234-5678',
        'person' => '山田様',
        'note' => __('messages.customers.notes.wave'),
        'last_updated' => '2024-02-20 14:05',
        'created_at' => '2023-10-11',
    ];
@endphp

@section('content')
    <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <p class="text-sm text-black/60">{{ __('messages.customers.form.description') }}</p>
                        <div class="inline-flex items-center gap-2 rounded-md bg-accent/10 px-3 py-1 text-xs font-medium text-accent">
                            <span class="inline-flex h-2 w-2 rounded-full bg-accent"></span>
                            {{ __('messages.customers.form.status.editing') }}
                        </div>
                    </div>

                    <form class="space-y-6" action="#" method="POST">
                        <div class="grid gap-6 md:grid-cols-2">
                            <label class="form-field md:col-span-2">
                                <span class="form-label">{{ __('messages.customers.form.fields.name') }}</span>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-input"
                                    placeholder="{{ __('messages.customers.form.placeholders.name') }}"
                                    value="{{ $customer['name'] }}"
                                >
                            </label>

                            <label class="form-field">
                                <span class="form-label">{{ __('messages.customers.form.fields.contact') }}</span>
                                <input
                                    type="tel"
                                    name="contact"
                                    class="form-input"
                                    placeholder="{{ __('messages.customers.form.placeholders.contact') }}"
                                    value="{{ $customer['contact'] }}"
                                >
                            </label>

                            <label class="form-field">
                                <span class="form-label">{{ __('messages.customers.form.fields.person') }}</span>
                                <input
                                    type="text"
                                    name="person"
                                    class="form-input"
                                    placeholder="{{ __('messages.customers.form.placeholders.person') }}"
                                    value="{{ $customer['person'] }}"
                                >
                            </label>
                        </div>

                        <label class="form-field">
                            <span class="form-label">{{ __('messages.customers.form.fields.note') }}</span>
                            <textarea
                                name="note"
                                rows="4"
                                class="form-input"
                                placeholder="{{ __('messages.customers.form.placeholders.note') }}"
                            >{{ $customer['note'] }}</textarea>
                        </label>

                        <div class="flex flex-wrap items-center justify-end gap-3">
                            <a href="{{ route('customers') }}" class="btn-secondary">{{ __('messages.customers.form.buttons.cancel') }}</a>
                            <button type="submit" class="btn-primary">{{ __('messages.customers.form.buttons.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <aside class="space-y-4 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-base font-semibold text-accent">{{ __('messages.customers.form.sidebar.title') }}</h2>
            <dl class="space-y-3 text-sm text-black/70">
                <div class="flex items-start justify-between gap-4">
                    <dt class="font-medium text-black/80">{{ __('messages.customers.form.sidebar.labels.customer_code') }}</dt>
                    <dd>CU-00045</dd>
                </div>
                <div class="flex items-start justify-between gap-4">
                    <dt class="font-medium text-black/80">{{ __('messages.customers.form.sidebar.labels.created_at') }}</dt>
                    <dd>{{ $customer['created_at'] }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4">
                    <dt class="font-medium text-black/80">{{ __('messages.customers.form.sidebar.labels.last_updated') }}</dt>
                    <dd>{{ $customer['last_updated'] }}</dd>
                </div>
            </dl>
            <div class="rounded-md bg-black/5 p-3 text-xs text-black/60">
                {{ __('messages.customers.form.sidebar.note') }}
            </div>
        </aside>
    </div>
@endsection
