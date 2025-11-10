{{-- resources/views/products.blade.php --}}
@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.products.title'))
@section('page-title', __('messages.products.title'))

@section('content')
@php
    /** @var \Illuminate\Database\Eloquent\Collection<int,\App\Models\Product> $products */
    // ปุ่ม fallback (ถ้า AppServiceProvider ยังไม่ share มา)
    $btn = $btnPalette ?? [
        'primary' => 'inline-flex items-center gap-2 rounded-full bg-accent text-white px-4 py-2 text-xs font-semibold shadow-sm hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent',
        'soft'    => 'inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent',
        'danger'  => 'inline-flex items-center gap-2 rounded-full border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500',
    ];
@endphp

<div class="space-y-6">
    {{-- Flash --}}
    @if (session('status'))
        <div class="flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
            <svg class="mt-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.06l2.5 2.5a.75.75 0 001.128-.088l4-5.5z" clip-rule="evenodd"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
        {{-- Header --}}
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.products.title') }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ __('messages.products.description') }}</p>
                </div>
                <a href="{{ route('products.form') }}" class="{{ $btn['primary'] }}">
                    {{-- plus icon --}}
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10 4v12M4 10h12"/></svg>
                    <span>{{ __('messages.products.actions.create') }}</span>
                </a>
            </div>
        </div>

        @if ($products->isEmpty())
            <div class="px-6 py-12 text-center text-sm text-slate-600">
                <p class="text-base font-semibold text-slate-900">{{ __('messages.products.empty.title') }}</p>
                <p class="mt-2">{{ __('messages.products.empty.description') }}</p>
            </div>
        @else
            {{-- Desktop table --}}
            <div class="hidden md:block">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.products.table.code') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.products.table.name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.products.table.unit') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.products.table.price') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('messages.products.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white text-sm text-slate-800">
                            @foreach ($products as $product)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-3 font-medium text-slate-900">{{ $product->code }}</td>
                                    <td class="px-6 py-3">{{ $product->name }}</td>
                                    <td class="px-6 py-3">{{ $product->unit ?? '—' }}</td>
                                    <td class="px-6 py-3 text-right">¥{{ number_format($product->price ?? 0) }}</td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('products.form', ['product' => $product->getKey()]) }}" class="{{ $btn['soft'] }}">
                                                {{-- edit icon --}}
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z"/><path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z"/></svg>
                                                <span>{{ __('messages.products.actions.edit') }}</span>
                                            </a>
<form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('messages.products.actions.confirm_delete', ['code' => $product->code ?? '—', 'name' => $product->name]) }}');">
    @csrf @method('DELETE')
    {{-- VVVV พี่โดนัทแก้ class ในปุ่มนี้ VVVV --}}
    <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-red-600 text-white rounded-full hover:bg-red-700">
        {{-- trash icon --}}
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 3a1.5 1.5 0 00-1.415 1.028L6.382 5H4.75a.75.75 0 000 1.5h.32l.55 8.25A2.25 2.25 0 007.863 17h4.274a2.25 2.25 0 002.243-2.25l.55-8.25h.32a.75.75 0 000-1.5H13.62l-.703-1.972A1.5 1.5 0 0011.5 3h-3zm2.651 2l.427 1.197a.75.75 0 00.707.503h1.687l-.52 7.8a.75.75 0 01-.748.7H7.53a.75.75 0 01-.748-.7l-.52-7.8h1.687a.75.75 0 00.707-.503L9.272 5h1.879z" clip-rule="evenodd"/></svg>
        <span>{{ __('messages.products.actions.delete') }}</span>
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

            {{-- Mobile cards --}}
            <ul class="divide-y divide-slate-100 md:hidden">
                @foreach ($products as $product)
                    <li class="p-4">
                        <div class="rounded-2xl ring-1 ring-slate-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs text-slate-500">{{ __('messages.products.table.code') }}: <span class="font-medium text-slate-900">{{ $product->code }}</span></div>
                                    <div class="mt-0.5 font-medium text-slate-900">{{ $product->name }}</div>
                                </div>
                                <div class="text-right text-sm font-semibold text-slate-900">¥{{ number_format($product->price ?? 0) }}</div>
                            </div>

                            <div class="mt-2 text-xs text-slate-600">
                                {{ __('messages.products.table.unit') }}: {{ $product->unit ?? '—' }}
                            </div>

                            <div class="mt-3 flex items-center justify-end gap-2">
                                <a href="{{ route('products.form', ['product' => $product->getKey()]) }}" class="{{ $btn['soft'] }}">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z"/><path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z"/></svg>
                                    <span>{{ __('messages.products.actions.edit') }}</span>
                                </a>
<form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('messages.products.actions.confirm_delete', ['code' => $product->code ?? '—', 'name' => $product->name]) }}');">
    @csrf @method('DELETE')
    {{-- VVVV พี่โดนัทแก้ class ในปุ่มนี้ VVVV --}}
    <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-red-600 text-white rounded-full hover:bg-red-700">
        {{-- trash icon --}}
        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 3a1.5 1.5 0 00-1.415 1.028L6.382 5H4.75a.75.75 0 000 1.5h.32l.55 8.25A2.25 2.25 0 007.863 17h4.274a2.25 2.25 0 002.243-2.25l.55-8.25h.32a.75.75 0 000-1.5H13.62l-.703-1.972A1.5 1.5 0 0011.5 3h-3zm2.651 2l.427 1.197a.75.75 0 00.707.503h1.687l-.52 7.8a.75.75 0 01-.748.7H7.53a.75.75 0 01-.748-.7l-.52-7.8h1.687a.75.75 0 00.707-.503L9.272 5h1.879z" clip-rule="evenodd"/></svg>
        <span>{{ __('messages.products.actions.delete') }}</span>
    </button>
</form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
