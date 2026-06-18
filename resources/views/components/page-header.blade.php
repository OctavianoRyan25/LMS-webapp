{{--
|--------------------------------------------------------------------------
| components/page-header.blade.php
|--------------------------------------------------------------------------
|
| CARA PAKAI:
|
|   <x-page-header title="Manajemen Kursus">
|       <x-slot:actions>
|           <a href="{{ route('admin.courses.create') }}" class="ep-btn-primary">
|               + Tambah Kursus
|           </a>
|       </x-slot:actions>
|   </x-page-header>
--}}

@props([
    'title'    => '',
    'subtitle' => null,
])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="font-display font-bold text-xl text-slate-800 dark:text-white">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-sm text-slate-500 mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex items-center gap-2 flex-shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>