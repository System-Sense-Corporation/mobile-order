@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.customers.title'))

@section('page-title', __('messages.customers.title'))

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-black/60">
                {{ __('messages.customers.description') }}
            </p>

            @unless ($customersAreDemo)
                <a href="{{ route('customers.create') }}" class="btn-primary">
                    {{ __('messages.customers.buttons.create') }}
                </a>
            @else
                <span class="inline-flex items-center rounded-md border border-dashed border-black/20 px-4 py-2 text-sm text-black/50">
                    {{ __('messages.customers.demo_actions_disabled') }}
                </span>
            @endunless
        </div>

        @if ($customersAreDemo)
            <div class="rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                {{ __('messages.customers.demo_notice') }}
            </div>
        @endif

        @if ($customers->isEmpty())
            <div class="rounded-lg bg-white p-6 text-sm text-black/50 shadow-sm ring-1 ring-black/5">
                {{ __('messages.customers.empty') }}
            </div>
        @else
            <div class="space-y-4">
                @foreach ($customers as $customer)
                    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-black/5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="space-y-2">
                                <h2 class="text-lg font-semibold text-accent">{{ $customer->name }}</h2>

                                <dl class="space-y-1 text-sm text-black/70">
                                    <div>
                                        <dt class="font-medium text-black/60">{{ __('messages.customers.contact_person') }}</dt>
                                        <dd>{{ $customer->contact_person ?: __('messages.customers.not_provided') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-medium text-black/60">{{ __('messages.customers.contact_label') }}</dt>
                                        <dd>{{ $customer->contact ?: __('messages.customers.not_provided') }}</dd>
                                    </div>
                                </dl>
                            </div>

                            @unless ($customersAreDemo)
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn-secondary">
                                        {{ __('messages.customers.buttons.edit') }}
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('customers.destroy', $customer) }}"
                                        onsubmit="return confirm('{{ __('messages.customers.confirm_delete') }}');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">
                                            {{ __('messages.customers.buttons.delete') }}
                                        </button>
                                    </form>
                                </div>
                            @endunless
                        </div>

                        @if ($customer->notes)
                            <div class="mt-4 space-y-1 text-sm">
                                <p class="font-medium text-black/60">{{ __('messages.customers.notes_label') }}</p>
                                <p class="text-black/70">{{ $customer->notes }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
