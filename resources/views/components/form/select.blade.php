{{--
|--------------------------------------------------------------------------
| components/form/select.blade.php
|--------------------------------------------------------------------------
|
| CARA PAKAI:
|
|   <x-form.select name="status" label="Status" :required="true">
|       <option value="">-- Pilih Status --</option>
|       <option value="active"   @selected(old('status', $course->status) === 'active')>Aktif</option>
|       <option value="draft"    @selected(old('status', $course->status) === 'draft')>Draf</option>
|       <option value="archived" @selected(old('status', $course->status) === 'archived')>Arsip</option>
|   </x-form.select>
|
|   {{-- Atau dari array collection --}}
{{-- |   <x-form.select name="category_id" label="Kategori">
|       <option value="">Semua Kategori</option>
|       @foreach ($categories as $cat)
|           <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
|               {{ $cat->name }}
|           </option>
|       @endforeach
|   </x-form.select> --}}
{{-- --}}

@props([
    'name' => '',
    'label' => null,
    'hint' => null,
    'required' => false,
])

@php $error = $errors->first($name); @endphp

<div class="space-y-1">
    @if ($label)
        <label for="{{ $name }}" class="ep-label">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    <select id="{{ $name }}" name="{{ $name }}" @if ($required) required @endif
        {{ $attributes->merge([
            'class' => 'ep-select ' . ($error ? 'ep-input-error' : ''),
        ]) }}>
        {{ $slot }}
    </select>

    @if ($hint && !$error)
        <p class="ep-hint">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="ep-error-msg">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $error }}
        </p>
    @endif
</div>
