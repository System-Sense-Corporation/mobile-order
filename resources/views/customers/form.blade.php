@extends('layouts.app')

@php
    $isEdit = $customer->exists;
    $pageTitle = $isEdit
        ? __('messages.customers.form.edit_title')
        : __('messages.customers.form.create_title');
@endphp

@section('title', __('messages.app.name') . ' - ' . $pageTitle)

@section('page-title', $pageTitle)

@section('content')

    <div class="space-y-6">
        <a href="{{ route('customers.index') }}" class="btn-secondary">
            {{ __('messages.customers.buttons.back') }}
        </a>

        <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <form
                method="POST"
                action="{{ $isEdit ? route('customers.update', $customer) : route('customers.store') }}"
                class="space-y-5"
            >
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <label class="form-field">
                    <span class="form-label">{{ __('messages.customers.form.fields.name') }}</span>
                    <input
                        type="text"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $customer->name) }}"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>

                <label class="form-field">
                    <span class="form-label">{{ __('messages.customers.form.fields.contact_person') }}</span>
                    <input
                        type="text"
                        name="contact_person"
                        class="form-input"
                        value="{{ old('contact_person', $customer->contact_person) }}"
                    >
                    @error('contact_person')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>

                <label class="form-field">
                    <span class="form-label">{{ __('messages.customers.form.fields.contact') }}</span>
                    <input
                        type="text"
                        name="contact"
                        class="form-input"
                        value="{{ old('contact', $customer->contact) }}"
                    >
                    @error('contact')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>

                <label class="form-field">
                    <span class="form-label">{{ __('messages.customers.form.fields.notes') }}</span>
                    <textarea
                        name="notes"
                        rows="4"
                        class="form-input"
                        placeholder="{{ __('messages.customers.form.placeholders.notes') }}"
                    >{{ old('notes', $customer->notes ?? '') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </label>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('customers.index') }}" class="btn-secondary">
                        {{ __('messages.customers.buttons.cancel') }}
                    </a>
                    <button type="submit" class="btn-primary">
                        {{ $isEdit ? __('messages.customers.buttons.update') : __('messages.customers.buttons.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
