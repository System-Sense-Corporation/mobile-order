@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.admin_users.title'))
@section('page-title', __('messages.admin_users.title'))

@section('content')
    @php
        // แผนที่ข้อความสำหรับแสดงผล
        $ROLE_MAP   = (array) trans('messages.admin_users.roles');
        $DEPT_MAP   = (array) trans('messages.admin_users.form.department.options');
        $STATUS_MAP = (array) trans('messages.admin_users.statuses');
    @endphp

    <div class="space-y-6">
        {{-- Header + Actions --}}
        <div class="flex flex-col gap-4 rounded-lg bg-white p-5 shadow-sm ring-1 ring-black/5 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-accent">{{ __('messages.admin_users.title') }}</h2>
                <p class="mt-1 text-sm text-black/60">{{ __('messages.admin_users.description') }}</p>
            </div>

            {{-- VVVV Toolbar (Search + Filter + Create) ที่เราแก้แล้ว VVVV --}}
            <div class="flex w-full flex-col gap-3 md:w-auto md:flex-row md:items-center">
                
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col items-stretch gap-3 md:flex-row md:items-center">

                    {{-- 1. Search Bar --}}
                    <label for="user-search" class="relative flex-1 md:w-64">
                        <span class="sr-only">{{ __('messages.admin_users.placeholders.search') }}</span>
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-black/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.9 14.32a7 7 0 1 1 1.414-1.414l3.39 3.39a1 1 0 0 1-1.414 1.414l-3.39-3.39ZM14 9a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input
                            type="search"
                            id="user-search"
                            name="search"
                            class="form-input w-full pl-9"
                            placeholder="{{ __('messages.admin_users.placeholders.search') }}"
                            value="{{ request('search') }}"
                        >
                    </label>

                    {{-- 2. Filter Dropdown --}}
                    <div class="flex items-center gap-2">
                        @php
                            $roleOptions = (array) trans('messages.admin_users.roles');
                        @endphp
                        <label for="authority-filter" class="shrink-0 text-xs font-semibold text-slate-600 whitespace-nowrap">
                            {{ __('messages.admin_users.filters.permission') }}:
                        </label>
                        <select
                            id="authority-filter"
                            name="authority"
                            class="form-select"
                            onchange="this.form.submit()"
                        >
                            <option value="">— {{ __('messages.admin_users.filters.all_permissions') }} —</option>
                            @foreach ($roleOptions as $roleKey => $roleLabel)
                                <option value="{{ $roleKey }}" @selected(request('authority') === $roleKey)>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                {{-- 3. Create Button (อยู่นอกฟอร์ม) --}}
                <a href="{{ route('admin.users.form') }}" class="inline-flex items-center gap-2 rounded-full bg-accent text-white px-4 py-2 text-xs font-semibold shadow-sm hover:bg-accent/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent whitespace-nowrap">
                    {{-- plus icon --}}
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10 4v12M4 10h12"/></svg>
                    <span>{{ __('messages.admin_users.actions.create') }}</span>
                </a>
            </div>
        </div>

        {{-- Flash --}}
        @if (session('status'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-black/5">
            {{-- VVVV 2. พี่โดนัท "แก้" Desktop table (ยุบ) VVVV --}}
            <div class="hidden md:block">
                {{-- (เรา "ลบ" overflow-x-auto ทิ้ง... เพราะมันจะไม่ล้นแล้ว) --}}
                <div>
                    <table class="min-w-full divide-y divide-black/10 text-left text-sm">
                        <thead class="bg-black/5 text-xs uppercase tracking-wide text-black/60">
                            <tr>
                                {{-- (เหลือ 4 คอลัมน์) --}}
                                <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.user_id') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.name') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('messages.admin_users.table.department') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('messages.admin_users.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                            @forelse ($users as $index => $user)
                                <tr class="hover:bg-black/5">
                                    {{-- 1. รหัสผู้ใช้ --}}
                                    <td class="px-4 py-3 font-medium text-accent/90">{{ $user['user_id'] }}</td>
                                    
                                    {{-- 2. (ยุบ) ชื่อ / อีเมล --}}
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-black/80">{{ $user['name'] }}</div>
                                        <div class="text-xs text-black/60">{{ $user['email'] }}</div>
                                    </td>
                                    
                                    {{-- 3. (ยุบ) แผนก / สิทธิ์ --}}
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-black/70">{{ $user['department'] }}</div>
                                        <div class="text-xs text-black/60">{{ __('messages.admin_users.roles.' . $user['authority']) }}</div>
                                    </td>

                                    {{-- 4. การดำเนินการ --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a
                                                href="{{ route('admin.users.edit', $user['user_id']) }}"
                                                class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                            >
                                                {{-- edit icon --}}
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z"/><path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z"/></svg>
                                                <span>{{ __('messages.admin_users.actions.edit') }}</span>
                                            </a>

                                            <form
                                                action="{{ route('admin.users.destroy', $user['user_id']) }}"
                                                method="POST"
                                                class="inline-flex"
                                                onsubmit="return confirm('{{ __('messages.admin_users.confirm_delete', ['name' => $user['name']]) }}');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-red-600 text-white rounded-full hover:bg-red-700"
                                                >
                                                    {{-- delete icon --}}
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 3a1.5 1.5 0 00-1.415 1.028L6.382 5H4.75a.75.75 0 000 1.5h.32l.55 8.25A2.25 2.25 0 007.863 17h4.274a2.25 2.25 0 002.243-2.25l.55-8.25h.32a.75.75 0 000-1.5H13.62l-.703-1.972A1.5 1.5 0 0011.5 3h-3zm2.651 2l.427 1.197a.75.75 0 00.707.503h1.687l-.52 7.8a.75.75 0 01-.748.7H7.53a.75.75 0 01-.748-.7l-.52-7.8h1.687a.75.75 0 00.707-.503L9.272 5h1.879z" clip-rule="evenodd"/></svg>
                                                    <span>{{ __('messages.admin_users.actions.delete') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- (เหลือ 4 คอลัมน์) --}}
                                    <td colspan="4" class="px-4 py-12 text-center text-black/50">
                                        {{ __('messages.admin_users.empty') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- VVVV 3. พี่โดนัท "แก้" Mobile cards ให้ยุบเหมือนกัน VVVV --}}
            <ul class="divide-y divide-black/5 md:hidden">
                @forelse ($users as $index => $user)
                    <li class="p-4">
                        <div class="rounded-lg ring-1 ring-black/10 p-4">
                            <div class="flex items-start justify-between gap-3">
                                {{-- Info --}}
                                <div>
                                    <div class="text-xs text-accent/90">{{ $user['user_id'] }}</div>
                                    <div class="mt-0.5 font-semibold text-black/80">{{ $user['name'] }}</div>
                                </div>
                                {{-- Status (อันนี้ใน Mobile ยังเก็บไว้ได้... เพราะมันไม่ล้น) --}}
                                <div class="shrink-0">
                                    <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold text-accent">
                                        {{ __('messages.admin_users.statuses.' . $user['status']) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Details (ยุบรวม) --}}
                            <div class="mt-3 space-y-1 text-xs text-black/70">
                                <div>{{ __('messages.admin_users.table.department') }}: <span class="font-medium">{{ $user['department'] }}</span></div>
                                <div>{{ __('messages.admin_users.table.authority') }}: <span class="font-medium">{{ __('messages.admin_users.roles.' . $user['authority']) }}</span></div>
                                <div>{{ __('messages.admin_users.table.email') }}: <span class="font-medium">{{ $user['email'] }}</span></div>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-4 flex items-center justify-end gap-2">
                                <a
                                    href="{{ route('admin.users.edit', $user['user_id']) }}"
                                    class="inline-flex items-center gap-2 rounded-full border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent"
                                >
                                    {{-- edit icon --}}
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793z"/><path d="M4 13.5V16h2.5l7.086-7.086-2.828-2.828L4 13.5z"/></svg>
                                    <span>{{ __('messages.admin_users.actions.edit') }}</span>
                                </a>

                                <form
                                    action="{{ route('admin.users.destroy', $user['user_id']) }}"
                                    method="POST"
                                    class="inline-flex"
                                    onsubmit="return confirm('{{ __('messages.admin_users.confirm_delete', ['name' => $user['name']]) }}');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 bg-red-600 text-white rounded-full hover:bg-red-700"
                                    >
                                        {{-- delete icon --}}
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.5 3a1.5 1.5 0 00-1.415 1.028L6.382 5H4.75a.75.75 0 000 1.5h.32l.55 8.25A2.25 2.25 0 007.863 17h4.274a2.25 2.25 0 002.243-2.25l.55-8.25h.32a.75.75 0 000-1.5H13.62l-.703-1.972A1.5 1.5 0 0011.5 3h-3zm2.651 2l.427 1.197a.75.75 0 00.707.503h1.687l-.52 7.8a.75.75 0 01-.748.7H7.53a.75.75 0 01-.748-.7l-.52-7.8h1.687a.75.75 0 00.707-.503L9.272 5h1.879z" clip-rule="evenodd"/></svg>
                                        <span>{{ __('messages.admin_users.actions.delete') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    {{-- (ข้อความ "ไม่พบ" สำหรับ Mobile) --}}
                    <li class="px-4 py-12 text-center text-black/50">
                        {{ __('messages.admin_users.empty') }}
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection