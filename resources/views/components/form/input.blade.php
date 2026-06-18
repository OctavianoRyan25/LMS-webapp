{{--
|--------------------------------------------------------------------------
| components/form/input.blade.php  —  Input Text Field
|--------------------------------------------------------------------------
|
| PROPS:
|   $name     → nama field (required)
|   $label    → label input
|   $type     → 'text' | 'email' | 'password' | 'number' | 'date' | ...
|   $value    → nilai default (old() atau model)
|   $hint     → teks hint di bawah input
|   $required → bool
|   $prefix   → icon/teks di kiri input
|   $suffix   → icon/teks di kanan input
|
| CARA PAKAI:
|
|   <x-form.input name="name"    label="Nama Lengkap" :required="true" />
|   <x-form.input name="email"   label="Email" type="email" hint="Gunakan email aktif" />
|   <x-form.input name="search"  prefix="🔍" placeholder="Cari..." />
--}}

@props([
    'name'     => '',
    'label'    => null,
    'type'     => 'text',
    'value'    => null,
    'hint'     => null,
    'required' => false,
    'prefix'   => null,
    'suffix'   => null,
])

@php
    $error    = $errors->first($name);
    $oldValue = old($name, $value);
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $name }}" class="ep-label">
            {{ $label }}
            @if($required) <span class="text-red-500 ml-0.5">*</span> @endif
        </label>
    @endif

    <div class="relative">
        @if($prefix)
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none text-sm">
                {{ $prefix }}
            </span>
        @endif

        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $oldValue }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'ep-input ' . ($prefix ? 'pl-10 ' : '') . ($suffix ? 'pr-10 ' : '') . ($error ? 'ep-input-error' : '')
            ]) }}
        >

        @if($suffix)
            <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 pointer-events-none text-sm">
                {{ $suffix }}
            </span>
        @endif
    </div>

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