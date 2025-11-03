@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.products.title'))

@section('page-title', __('messages.products.title'))

@section('content')
    @php
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products */
    @endphp
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
            <div class="border-b border-black/5 bg-black/5 px-6 py-4">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('messages.products.title') }}</h2>
                        <p class="mt-1 text-sm text-slate-600">{{ __('messages.products.description') }}</p>
                    </div>
                    <a href="{{ route('products.form') }}" class="btn-primary">
                        {{ __('messages.products.actions.create') }}
                    </a>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="px-6 py-12 text-center text-sm text-slate-600">
                    <p class="text-base font-semibold text-slate-900">{{ __('messages.products.empty.title') }}</p>
                    <p class="mt-2">{{ __('messages.products.empty.description') }}</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/10">
                        <thead class="bg-white text-left text-xs font-semibold uppercase tracking-wide text-black/60">
                            <tr>
                                <th class="px-4 py-3">{{ __('messages.products.table.code') }}</th>
                                <th class="px-4 py-3">{{ __('messages.products.table.name') }}</th>
                                <th class="px-4 py-3">{{ __('messages.products.table.unit') }}</th>
                                <th class="px-4 py-3 text-right">{{ __('messages.products.table.price') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 text-sm">
                            @foreach ($products as $product)
                                <tr class="hover:bg-black/5">
                                    <td class="px-4 py-3 font-medium text-slate-900">{{ $product->code }}</td>
                                    <td class="px-4 py-3 text-slate-800">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $product->unit ?? '—' }}</td>
                                    <td class="px-4 py-3 text-right text-slate-900">¥{{ number_format($product->price ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
