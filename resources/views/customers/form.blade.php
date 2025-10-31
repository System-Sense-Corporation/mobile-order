@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.customers.form.title'))

@section('page-title', __('messages.customers.form.title'))

@section('content')
    @php
        /** @var \App\Models\Customer $customer */
        $customer = $customer ?? new \App\Models\Customer();
        $isEditing = $customer->exists;
        $customerCode = $isEditing ? sprintf('CU-%05d', $customer->id) : '—';
        $createdAt = $isEditing && $customer->created_at
            ? $customer->created_at->format('Y-m-d H:i')
            : '—';
        $updatedAt = $isEditing && $customer->updated_at
            ? $customer->updated_at->format('Y-m-d H:i')
            : '—';
    @endphp
    <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <p class="text-sm text-black/60">{{ __('messages.customers.form.description') }}</p>
                        <div class="inline-flex items-center gap-2 rounded-md bg-accent/10 px-3 py-1 text-xs font-medium text-accent">
                            <span class="inline-flex h-2 w-2 rounded-full bg-accent"></span>
                            {{ __('messages.customers.form.status.' . ($isEditing ? 'editing' : 'creating')) }}
                        </div>
                    </div>

                    <form class="space-y-6" method="POST" action="{{ route('customers.store') }}">
                        @csrf
                        <div class="grid gap-6 md:grid-cols-2">
                            <label class="form-field md:col-span-2">
                                <span class="form-label">{{ __('messages.customers.form.fields.name') }}</span>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-input"
                                    placeholder="{{ __('messages.customers.form.placeholders.name') }}"
                                    value="{{ old('name', $customer->name) }}"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </label>

                            <label class="form-field">
                                <span class="form-label">{{ __('messages.customers.form.fields.contact') }}</span>
                                <input
                                    type="tel"
                                    name="contact"
                                    class="form-input"
                                    placeholder="{{ __('messages.customers.form.placeholders.contact') }}"
                                    value="{{ old('contact', $customer->contact) }}"
                                >
                                @error('contact')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </label>

                            <label class="form-field">
                                <span class="form-label">{{ __('messages.customers.form.fields.person') }}</span>
                                <input
                                    type="text"
                                    name="contact_person"
                                    class="form-input"
                                    placeholder="{{ __('messages.customers.form.placeholders.person') }}"
                                    value="{{ old('contact_person', $customer->contact_person) }}"
                                >
                                @error('contact_person')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </label>
                        </div>

                        <label class="form-field">
                            <span class="form-label">{{ __('messages.customers.form.fields.note') }}</span>
                            <textarea
                                name="notes"
                                rows="4"
                                class="form-input"
                                placeholder="{{ __('messages.customers.form.placeholders.note') }}"
                            >{{ old('notes', $customer->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
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
                    <dd>{{ $customerCode }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4">
                    <dt class="font-medium text-black/80">{{ __('messages.customers.form.sidebar.labels.created_at') }}</dt>
                    <dd>{{ $createdAt }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4">
                    <dt class="font-medium text-black/80">{{ __('messages.customers.form.sidebar.labels.last_updated') }}</dt>
                    <dd>{{ $updatedAt }}</dd>
                </div>
            </dl>
            <div class="rounded-md bg-black/5 p-3 text-xs text-black/60">
                {{ __('messages.customers.form.sidebar.note') }}
            </div>
        </aside>
    </div>
@endsection
