@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.customers.title'))
@section('page-title', __('messages.customers.title'))

@section('content')
<div class="space-y-6">
    {{-- flash --}}
    @if (session('status'))
        <div class="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            <svg class="mt-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.799-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.128-.088l4-5.5z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- header card --}}
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

        {{-- ===== Mobile cards (< md) ===== --}}
        <div class="p-4 md:hidden space-y-3">
            @forelse ($customers as $customer)
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-900">{{ $customer->name }}</div>
                            @if($customer->contact_person)
                                <div class="text-sm text-gray-600 mt-0.5">
                                    {{ __('messages.customers.contact_person') }}: {{ $customer->contact_person }}
                                </div>
                            @endif
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a href="{{ route('customers.edit', $customer) }}"
                               class="text-xs px-3 py-1 border rounded-lg border-gray-300 hover:bg-gray-100">
                                {{ __('messages.customers.actions.edit') }}
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                  onsubmit="return confirm('{{ __('messages.customers.actions.confirm_delete', ['name' => $customer->name]) }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    {{ __('messages.customers.actions.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-2 text-sm">
                        <div class="flex justify-between gap-3">
                            <span class="text-gray-500">{{ __('messages.customers.contact_label') }}</span>
                            <span class="text-gray-800 truncate">{{ $customer->contact ?? '—' }}</span>
                        </div>
                        <div class="flex gap-3">
                            <span class="text-gray-500 shrink-0">{{ __('messages.customers.internal_note_label') }}:</span>
                            <span class="text-gray-700 break-words">
                                {{ trim($customer->notes ?? '') !== '' ? $customer->notes : '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-gray-200 bg-white p-6 text-center text-gray-500">
                    {{ __('messages.customers.empty.title') }}
                    <a href="{{ route('customers.form') }}" class="text-red-600 hover:underline">
                        {{ __('messages.customers.actions.create_short') }}
                    </a>
                </div>
            @endforelse
        </div>

        {{-- ===== Desktop table (>= md) ===== --}}
        <div class="hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                {{ __('messages.customers.table.customer') }}
                            </th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                {{ __('messages.customers.table.contact_person') }}
                            </th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                {{ __('messages.customers.table.contact') }}
                            </th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">
                                {{ __('messages.customers.table.internal_note') }}
                            </th>
                            <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">
                                {{ __('messages.customers.table.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($customers as $customer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-800">
                                    {{ $customer->contact_person ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-800 whitespace-nowrap">
                                    {{ $customer->contact ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    <div class="max-w-[40ch] truncate">
                                        {{ trim($customer->notes ?? '') !== '' ? $customer->notes : '—' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('customers.edit', $customer) }}"
                                       class="text-sm px-3 py-1 border rounded-lg border-gray-300 hover:bg-gray-100 mr-2">
                                        {{ __('messages.customers.actions.edit') }}
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('{{ __('messages.customers.actions.confirm_delete', ['name' => $customer->name]) }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-sm px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                            {{ __('messages.customers.actions.delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> {{-- /card --}}
</div>
@endsection
