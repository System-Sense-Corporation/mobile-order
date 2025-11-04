@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.profile.title'))
@section('page-title', __('messages.profile.title'))

@section('content')
  <div class="space-y-6">

    {{-- Flash message --}}
    @if (session('success'))
      <div class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-700">
        {{ session('success') }}
      </div>
    @endif

    {{-- Summary / Description --}}
    <p class="text-sm text-black/70">{{ __('messages.profile.description') }}</p>

    {{-- ===== A) อัปเดตข้อมูลบัญชี: ชื่อ / อีเมล / เบอร์โทร ===== --}}
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
      <div class="space-y-6">
        <div class="space-y-2">
          <h2 class="text-lg font-semibold text-accent">{{ __('messages.profile.sections.account_information.title') }}</h2>
          <p class="text-sm text-black/70">{{ __('messages.profile.sections.account_information.description') }}</p>
        </div>

        <form class="space-y-4" method="POST" action="{{ route('profile.update') }}">
          @csrf
          {{-- intent: ให้ Controller แยกว่ามาจากฟอร์มไหน --}}
          <input type="hidden" name="intent" value="profile">

          <label class="form-field">
            <span class="form-label">{{ __('messages.profile.sections.account_information.fields.name') }}</span>
            <input
              type="text"
              name="name"
              class="form-input"
              value="{{ old('name', $user->name ?? '') }}"
              autocomplete="name"
              required
            >
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </label>

          <label class="form-field">
            <span class="form-label">{{ __('messages.profile.sections.account_information.fields.email') }}</span>
            <input
              type="email"
              name="email"
              class="form-input"
              value="{{ old('email', $user->email ?? '') }}"
              autocomplete="email"
              required
            >
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </label>

          <label class="form-field">
            <span class="form-label">{{ __('messages.profile.sections.account_information.fields.department') }}</span>
            <select
              id="department"
              name="department"
              class="form-input"
            >
              <option value="">
                {{ __('messages.profile.sections.account_information.fields.department_placeholder') }}
              </option>
              @foreach ($departmentOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('department', $user->department ?? null) === $value)>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('department') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </label>

          <label class="form-field">
            <span class="form-label">{{ __('messages.profile.sections.account_information.fields.telephone') }}</span>
            <input
              type="tel"
              name="telephone"
              class="form-input"
              value="{{ old('telephone', $user->telephone ?? '') }}"
              autocomplete="tel"
              placeholder="0812345678"
            >
            @error('telephone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </label>

          <div class="flex justify-end">
            <button type="submit" class="btn-primary">
              {{ __('messages.profile.sections.account_information.button') }}
            </button>
          </div>
        </form>
      </div>
    </section>

    {{-- ===== B) เปลี่ยนรหัสผ่าน ===== --}}
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
      <div class="space-y-6">
        <div class="space-y-2">
          <h2 class="text-lg font-semibold text-accent">{{ __('messages.profile.sections.password.title') }}</h2>
          <p class="text-sm text-black/70">{{ __('messages.profile.sections.password.description') }}</p>
        </div>

        <form class="space-y-4" method="POST" action="{{ route('profile.update') }}">
          @csrf
          <input type="hidden" name="intent" value="password">

          <label class="form-field">
            <span class="form-label">{{ __('messages.profile.sections.password.fields.current') }}</span>
            <input
              type="password"
              name="current_password"
              class="form-input"
              autocomplete="current-password"
              required
            >
            @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </label>

          <div class="grid gap-4 md:grid-cols-2">
            <label class="form-field">
              <span class="form-label">{{ __('messages.profile.sections.password.fields.new') }}</span>
              <input
                type="password"
                name="password"
                class="form-input"
                autocomplete="new-password"
                required
              >
              @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </label>

            <label class="form-field">
              <span class="form-label">{{ __('messages.profile.sections.password.fields.confirmation') }}</span>
              <input
                type="password"
                name="password_confirmation"
                class="form-input"
                autocomplete="new-password"
                required
              >
            </label>
          </div>

          <p class="text-xs text-black/60">{{ __('messages.profile.sections.password.helper') }}</p>

          <div class="flex justify-end">
            <button type="submit" class="btn-primary">
              {{ __('messages.profile.sections.password.button') }}
            </button>
          </div>
        </form>
      </div>
    </section>

    {{-- ===== C) การจัดการบัญชี (คำเตือน/ปุ่มลบบัญชี – ยังไม่ผูกแบ็กเอนด์) ===== --}}
    <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
      <div class="space-y-6">
        <div class="space-y-2">
          <h2 class="text-lg font-semibold text-accent">{{ __('messages.profile.sections.account.title') }}</h2>
          <p class="text-sm text-black/70">{{ __('messages.profile.sections.account.description') }}</p>
        </div>

        <div class="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-700">
          <p>{{ __('messages.profile.sections.account.delete_warning') }}</p>
        </div>

        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
          <p class="text-xs text-black/60">{{ __('messages.profile.sections.account.support') }}</p>
          <button type="button" class="btn-danger">
            {{ __('messages.profile.sections.account.button') }}
          </button>
        </div>
      </div>
    </section>

  </div>
@endsection
