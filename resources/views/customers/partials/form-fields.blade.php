@php
    /** @var \App\Models\Customer|null $customer */
    $formCustomer = $customer ?? new \App\Models\Customer();
    $oldCustomerId = old('customer_id');
    $shouldUseOld = $oldCustomerId === null
        || ! $formCustomer->exists
        || ((string) $oldCustomerId === (string) $formCustomer->getKey());

    $nameValue = $shouldUseOld ? old('name', $formCustomer->name) : $formCustomer->name;
    $contactValue = $shouldUseOld ? old('contact', $formCustomer->contact) : $formCustomer->contact;
    $contactPersonValue = $shouldUseOld ? old('contact_person', $formCustomer->contact_person) : $formCustomer->contact_person;
    $notesValue = $shouldUseOld ? old('notes', $formCustomer->notes) : $formCustomer->notes;
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <label class="form-field md:col-span-2">
        <span class="form-label">{{ __('messages.customers.form.fields.name') }}</span>
        <input
            type="text"
            name="name"
            class="form-input"
            placeholder="{{ __('messages.customers.form.placeholders.name') }}"
            value="{{ $nameValue }}"
            required
        >
        @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </label>

    <label class="form-field">
        <span class="form-label">{{ __('messages.customers.form.fields.contact') }}</span>
        <input
            type="tel"
            name="contact"
            class="form-input"
            placeholder="{{ __('messages.customers.form.placeholders.contact') }}"
            value="{{ $contactValue }}"
        >
        @error('contact')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </label>

    <label class="form-field">
        <span class="form-label">{{ __('messages.customers.form.fields.person') }}</span>
        <input
            type="text"
            name="contact_person"
            class="form-input"
            placeholder="{{ __('messages.customers.form.placeholders.person') }}"
            value="{{ $contactPersonValue }}"
        >
        @error('contact_person')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </label>
</div>

<label class="form-field">
    <span class="form-label">{{ __('messages.customers.form.fields.note') }}</span>
    <textarea
        name="notes"
        rows="4"
        class="form-input"
        placeholder="{{ __('messages.customers.form.placeholders.note') }}"
    >{{ $notesValue }}</textarea>
    @error('notes')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</label>
