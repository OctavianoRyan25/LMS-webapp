{{--
|--------------------------------------------------------------------------
| components/badge.blade.php  —  Komponen Badge / Label Status
|--------------------------------------------------------------------------
|
| PROPS:
|   $color  → 'green' | 'red' | 'yellow' | 'blue' | 'gray' | 'purple'
|   $dot    → bool, tampilkan dot indicator (default: false)
|   $size   → 'sm' | 'md' (default: 'md')
|
| CARA PAKAI:
|
|   <x-badge color="green">Aktif</x-badge>
|   <x-badge color="yellow" :dot="true">Menunggu</x-badge>
|   <x-badge color="red" size="sm">Ditolak</x-badge>
|
| ATAU pakai helper Blade untuk status dinamis:
|
|   <x-badge :color="$course->statusColor()">{{ $course->status_label }}</x-badge>
|
| Tambahkan di Model Course.php:
|   public function statusColor(): string {
|       return match($this->status) {
|           'active'   => 'green',
|           'draft'    => 'yellow',
|           'archived' => 'gray',
|           default    => 'blue',
|       };
|   }
--}}

@props([
    'color' => 'blue',
    'dot'   => false,
    'size'  => 'md',
])

@php
    $colorMap = [
        'green'  => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
        'red'    => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
        'yellow' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
        'blue'   => 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400',
        'gray'   => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400',
        'purple' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/10 dark:text-violet-400',
    ];

    $dotColorMap = [
        'green'  => 'bg-emerald-500',
        'red'    => 'bg-red-500',
        'yellow' => 'bg-amber-500',
        'blue'   => 'bg-brand-500',
        'gray'   => 'bg-slate-400',
        'purple' => 'bg-violet-500',
    ];

    $sizeClass = $size === 'sm' ? 'px-2 py-0.5 text-xs' : 'px-2.5 py-1 text-xs';

    $classes = 'inline-flex items-center gap-1.5 rounded-full font-semibold '
             . ($colorMap[$color] ?? $colorMap['blue']) . ' '
             . $sizeClass;
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColorMap[$color] ?? 'bg-brand-500' }}"></span>
    @endif
    {{ $slot }}
</span>