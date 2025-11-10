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

    @php
        /** @var \Illuminate\Database\Eloquent\Collection<int,\App\Models\Customer> $customers */
        // ปุ่ม fallback (ถ้า AppServiceProvider ยังไม่ share มา... ก๊อปมาจากหน้า Products)
        $btn = $btnPalette ?? [
            'primary' => 'inline-flex items-center gap-2 rounded-full bg-accent text-white px-4 py-2 text-xs font-semibold shadow-sm hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent',
            'soft'    => 'inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent',
            'danger'  => 'inline-flex items-center gap-2 rounded-full border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500',
        ];
    @endphp

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
                {{-- VVVV 1. พี่โดนัทแก้ปุ่ม "สร้าง" ให้เหมือนหน้าอื่น VVVV --}}
                <a href="{{ route('customers.form') }}" class="{{ $btn['primary'] }}">
                    {{-- plus icon --}}
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10 4v12M4 10h12"/></svg>
                    <span>{{ __('messages.customers.actions.create') }}</span>
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
                        {{-- VVVV 2. พี่โดนัทแก้ปุ่ม Mobile VVVV --}}
                        <div class="flex items-center gap-2 shrink-0"> {{-- <--- เพิ่ม items-center --}}
                            <a href="{{ route('customers.edit', $customer) }}"
                               class="inline-flex items-center gap-2 rounded-full border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100">
                                {{-- edit icon --}}
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z"/><path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z"/></svg>
                                <span>{{ __('messages.customers.actions.edit') }}</span>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
                                  onsubmit="return confirm('{{ __('messages.customers.actions.confirm_delete', ['name' => $customer->name]) }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-red-600 text-white rounded-full hover:bg-red-700">
                                    {{-- delete icon --}}
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 3a1.5 1.5 0 00-1.415 1.028L6.382 5H4.75a.75.75 0 000 1.5h.32l.55 8.25A2.25 2.25 0 007.863 17h4.274a2.25 2.25 0 002.243-2.25l.55-8.25h.32a.75.75 0 000-1.5H13.62l-.703-1.972A1.5 1.5 0 0011.5 3h-3zm2.651 2l.427 1.197a.75.75 0 00.707.503h1.687l-.52 7.8a.75.75 0 01-.748.7H7.53a.75.75 0 01-.748-.7l-.52-7.8h1.687a.75.75 0 00.707-.503L9.272 5h1.879z" clip-rule="evenodd"/></svg>
                                    <span>{{ __('messages.customers.actions.delete') }}</span>
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
                                    {{-- VVVV 3. พี่โดนัทแก้ปุ่ม Desktop VVVV --}}
                                    <div class="flex items-center justify-end gap-2"> {{-- <--- เพิ่ม 'กล่อง' หุ้ม --}}
                                        <a href="{{ route('customers.edit', $customer) }}"
                                           class="inline-flex items-center gap-2 rounded-full border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100">
                                            {{-- edit icon --}}
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z"/><path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z"/></svg>
                                            <span>{{ __('messages.customers.actions.edit') }}</span>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}"
                                              method="POST"
                                              onsubmit="return confirm('{{ __('messages.customers.actions.confirm_delete', ['name' => $customer->name]) }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-red-600 text-white rounded-full hover:bg-red-700">
                                                {{-- delete icon --}}
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 3a1.5 1.5 0 00-1.415 1.028L6.382 5H4.75a.75.75 0 000 1.5h.32l.55 8.25A2.25 2.25 0 007.863 17h4.274a2.25 2.25 0 002.243-2.25l.55-8.25h.32a.75.75 0 000-1.5H13.62l-.703-1.972A1.5 1.5 0 0011.5 3h-3zm2.651 2l.427 1.197a.75.75 0 00.707.503h1.687l-.52 7.8a.75.75 0 01-.748.7H7.53a.75.75 0 01-.748-.7l-.52-7.8h1.687a.75.75 0 00.707-.503L9.272 5h1.879z" clip-rule="evenodd"/></svg>
                                                <span>{{ __('messages.customers.actions.delete') }}</span>
                                            </button>
                                        </form>
                                    </div>
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