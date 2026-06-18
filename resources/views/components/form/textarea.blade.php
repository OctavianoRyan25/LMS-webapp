{{--
|--------------------------------------------------------------------------
| components/form/textarea.blade.php
|--------------------------------------------------------------------------
|
| CARA PAKAI:
|
|   <x-form.textarea name="description" label="Deskripsi Kursus"
|       hint="Minimal 50 karakter." :rows="5" />
--}}

@props([
    'name'     => '',
    'label'    => null,
    'hint'     => null,
    'required' => false,
    'rows'     => 4,
    'value'    => null,
])

@php $error = $errors->first($name); @endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="ep-label">
            {{ $label }}
            @if($required) <span class="text-red-500 ml-0.5">*</span> @endif
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        {{ $attributes->merge([
            'class' => 'ep-input resize-y ' . ($error ? 'ep-input-error' : '')
        ]) }}
    >{{ old($name, $value) }}</textarea>

    @if($hint && !$error)
        <p class="ep-hint">{{ $hint }}</p>
    @endif

    @if($error)
        <p class="ep-error-msg">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $error }}
        </p>
    @endif
</div>