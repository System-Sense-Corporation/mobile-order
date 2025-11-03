@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.customers.title'))

@section('page-title', __('messages.customers.title'))

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-black/60">{{ __('messages.customers.description') }}</p>
        <a href="{{ route('customers.form') }}" class="btn-primary">{{ __('messages.customers.actions.create') }}</a>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-inset ring-emerald-500/20">
            {{ session('status') }}
        </div>
    @endif

    @if (!empty($customersAreDemo) && $customersAreDemo)
        <div class="mb-4 rounded-md bg-amber-50 px-4 py-3 text-sm text-amber-800 ring-1 ring-inset ring-amber-500/20">
            {{ __('messages.customers.demo_notice') }}
        </div>
    @endif

    @php
        $canManageCustomers = empty($customersAreDemo) || ! $customersAreDemo;
    @endphp

    <div class="space-y-4">
        @forelse ($customers as $customer)
            <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-black/5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-accent">{{ $customer->name }}</h2>
                        @if ($customer->contact_person)
                            <p class="text-sm text-black/60">
                                {{ __('messages.customers.contact_person') }}: {{ $customer->contact_person }}
                            </p>
                        @endif
                    </div>
                    <div class="text-sm">
                        <p class="font-medium">{{ __('messages.customers.contact_label') }}</p>
                        <p>{{ $customer->contact ?? '—' }}</p>
                    </div>
                </div>
                @if (! empty($customer->notes))
                    <p class="mt-3 text-sm text-black/70">{{ $customer->notes }}</p>
                @endif
                @if ($canManageCustomers)
                    @php
                        $shouldOpenEditor = old('customer_id') === (string) $customer->getKey() && $errors->any();
                    @endphp
                    <div class="mt-4 space-y-3 border-t border-black/5 pt-4">
                        <details class="group space-y-3" @if($shouldOpenEditor) open @endif>
                            <summary class="cursor-pointer text-sm font-semibold text-accent outline-none transition hover:text-accent/80 focus-visible:ring-2 focus-visible:ring-accent/60">
                                {{ __('messages.customers.actions.edit') }}
                            </summary>
                            <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                @include('customers.partials.form-fields', ['customer' => $customer])
                                <div class="flex flex-wrap items-center justify-end gap-3">
                                    <button type="submit" class="btn-primary">
                                        {{ __('messages.customers.actions.update') }}
                                    </button>
                                </div>
                            </form>
                        </details>
                        <form
                            method="POST"
                            action="{{ route('customers.destroy', $customer) }}"
                            class="flex justify-end"
                            onsubmit="return confirm('{{ __('messages.customers.actions.confirm_delete', ['name' => $customer->name]) }}');"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">
                                {{ __('messages.customers.actions.delete') }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="rounded-lg border border-dashed border-black/10 bg-white/60 p-10 text-center text-sm text-black/60">
                <p class="font-medium text-black/70">{{ __('messages.customers.empty.title') }}</p>
                <p class="mt-1">{{ __('messages.customers.empty.description') }}</p>
            </div>
        @endforelse
    </div>
@endsection
