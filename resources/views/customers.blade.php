@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.customers.title'))

@section('page-title', __('messages.customers.title'))

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                <svg class="mt-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.799-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.128-.088l4-5.5z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if (!empty($customersAreDemo) && $customersAreDemo)
            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                {{ __('messages.customers.demo_notice') }}
            </div>
        @endif

        @php
            $canManageCustomers = empty($customersAreDemo) || ! $customersAreDemo;
        @endphp

        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.customers.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ __('messages.customers.description') }}
                        </p>
                    </div>
                    <a href="{{ route('customers.form') }}" class="btn-primary">
                        {{ __('messages.customers.actions.create') }}
                    </a>
                </div>
            </div>

            @if ($customers->isEmpty())
                <div class="px-6 py-12 text-center text-sm text-slate-500">
                    <p class="font-medium text-slate-700">{{ __('messages.customers.empty.title') }}</p>
                    <p class="mt-1">{{ __('messages.customers.empty.description') }}</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($customers as $customer)
                        <li class="px-6 py-6">
                            <div class="space-y-5 md:space-y-6">
                                <div class="min-w-0 space-y-3">
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                                        <h3 class="text-base font-semibold text-slate-900">{{ $customer->name }}</h3>
                                        @if ($customer->contact_person)
                                            <span class="text-sm text-slate-500">
                                                {{ __('messages.customers.contact_person') }}: {{ $customer->contact_person }}
                                            </span>
                                        @endif
                                    </div>
                                    @if (! empty($customer->notes))
                                        <p class="text-sm text-slate-600">{{ $customer->notes }}</p>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm">
                                    <span class="font-medium text-slate-900">{{ __('messages.customers.contact_label') }}:</span>
                                    <span class="break-words text-slate-700">{{ $customer->contact ?? '' }}</span>
                                </div>

                                @if ($canManageCustomers)
                                    <div class="flex flex-wrap items-center gap-2 md:gap-3">
                                        <a
                                            href="{{ route('customers.form', ['customer' => $customer->id]) }}"
                                            class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                        >
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z" />
                                                <path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z" />
                                            </svg>
                                            <span>{{ __('messages.customers.actions.edit') }}</span>
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('customers.destroy', $customer) }}"
                                            onsubmit="return confirm('{{ __('messages.customers.actions.confirm_delete', ['name' => $customer->name]) }}');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-transparent bg-rose-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-rose-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M8.75 3A1.75 1.75 0 007 4.75V5H4.75a.75.75 0 000 1.5H5v8.25A2.25 2.25 0 007.25 17h5.5A2.25 2.25 0 0015 14.75V6.5h.25a.75.75 0 000-1.5H13v-.25A1.75 1.75 0 0011.25 3h-2.5zM11.5 5v-.25a.25.25 0 00-.25-.25h-2.5a.25.25 0 00-.25.25V5h3zm-3.75 3a.75.75 0 011.5 0v5a.75.75 0 01-1.5 0V8zm3.5 0a.75.75 0 011.5 0v5a.75.75 0 01-1.5 0V8z" clip-rule="evenodd" />
                                                </svg>
                                                <span>{{ __('messages.customers.actions.delete') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
