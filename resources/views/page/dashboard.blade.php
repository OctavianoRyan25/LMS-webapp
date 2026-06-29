@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- ── Header ── --}}
    <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-8">
        <div>
            <p class="text-xs font-semibold text-brand-500 uppercase tracking-widest mb-1">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
            <h2 class="font-display font-bold text-2xl text-slate-800 dark:text-white">
                Selamat datang kembali 👋
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Berikut ringkasan aktivitas platform EduPanel hari ini.
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.courses.create') }}"
                class="ep-btn-primary focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-navy-900 transition-all">
                <svg class="w-4 h-4" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Kursus
            </a>
        </div>
    </header>

    {{-- ── Stat Cards ── --}}
    @php
        $stats = [
            [
                'label' => 'Total Kursus',
                'value' => \App\Models\Course::count(),
                'sub' => 'Semua kursus terdaftar',
                'icon' =>
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
                'icon_bg' => 'bg-brand-50 dark:bg-brand-500/10 text-brand-500',
                'href' => route('admin.courses.index'),
            ],
            [
                'label' => 'Dipublikasi',
                'value' => \App\Models\Course::where('status', 'published')->count(),
                'sub' => 'Aktif & dapat diakses',
                'icon' =>
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'icon_bg' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500',
                'href' => route('admin.courses.index', ['status' => 'published']),
            ],
            [
                'label' => 'Draft',
                'value' => \App\Models\Course::where('status', 'draft')->count(),
                'sub' => 'Belum dipublikasi',
                'icon' =>
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>',
                'icon_bg' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-500',
                'href' => route('admin.courses.index', ['status' => 'draft']),
            ],
            [
                'label' => 'Diarsipkan',
                'value' => \App\Models\Course::where('status', 'archived')->count(),
                'sub' => 'Tidak aktif',
                'icon' =>
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>',
                'icon_bg' => 'bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400',
                'href' => route('admin.courses.index', ['status' => 'archived']),
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        @foreach ($stats as $stat)
            <a href="{{ $stat['href'] }}"
                class="ep-card stat-card group block focus-visible:ring-2 focus-visible:ring-brand-500 rounded-xl outline-none">
                <div class="ep-card-body">
                    <div class="flex items-start justify-between mb-5">
                        <div
                            class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 {{ $stat['icon_bg'] }}">
                            <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                {!! $stat['icon'] !!}
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-brand-400 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-all"
                            aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </div>
                    <p class="text-3xl font-display font-bold text-slate-800 dark:text-white leading-none mb-1.5">
                        {{ number_format($stat['value']) }}
                    </p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $stat['label'] }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $stat['sub'] }}</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- ── Main Grid ── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 xl:gap-8">

        {{-- Kursus Terbaru (2/3) --}}
        <section class="xl:col-span-2 ep-card overflow-hidden">
            <div
                class="ep-card-header flex items-center justify-between pb-4 border-b border-slate-100 dark:border-navy-700/50 mb-1">
                <div>
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-lg">Kursus Terbaru</h3>
                    <p class="text-xs text-slate-400 mt-0.5">5 kursus yang baru ditambahkan</p>
                </div>
                <a href="{{ route('admin.courses.index') }}"
                    class="text-sm text-brand-500 hover:text-brand-600 font-medium flex items-center gap-1.5 transition-colors">
                    Lihat semua
                    <svg class="w-4 h-4" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            @php
                $statusMap = [
                    'published' => ['color' => 'green', 'label' => 'Aktif'],
                    'draft' => ['color' => 'yellow', 'label' => 'Draf'],
                    'archived' => ['color' => 'gray', 'label' => 'Arsip'],
                ];
            @endphp

            <div class="divide-y divide-slate-100 dark:divide-navy-700">
                @foreach ($latestCourses as $course)
                    @php
                        $st = $statusMap[$course['status']] ?? ['color' => 'blue', 'label' => $course['status']];
                    @endphp
                    <div
                        class="flex items-center gap-4 px-2 py-4 sm:py-5 hover:bg-slate-50/50 dark:hover:bg-navy-800/20 transition-colors group rounded-xl">

                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center flex-shrink-0 text-lg shadow-sm"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-book-open-icon lucide-book-open">
                                <path d="M12 7v14" />
                                <path
                                    d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate leading-relaxed">
                                {{ $course->title }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $course->category }} &middot; {{ $course->updated_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Badge --}}
                        <x-badge :color="$st['color']" :dot="true" size="sm"
                            class="px-2.5 py-1">{{ $st['label'] }}</x-badge>

                        {{-- Edit link --}}
                        <a href="{{ route('admin.courses.edit', $course->id) }}"
                            class="ml-2 p-2 rounded-lg text-slate-300 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 opacity-0 group-hover:opacity-100 focus:opacity-100 transition-all outline-none"
                            title="Edit Kursus">
                            <svg class="w-4 h-4" aria-hidden="true" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Kolom Kanan (1/3) --}}
        <aside class="space-y-6">

            {{-- Status Overview --}}
            <div class="ep-card">
                <div class="ep-card-header pb-4 border-b border-slate-100 dark:border-navy-700/50 mb-4">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-lg">Status Kursus</h3>
                </div>
                <div class="ep-card-body space-y-5">
                    @php
                        $total = max(\App\Models\Course::count(), 1);
                        $overview = [
                            [
                                'label' => 'Dipublikasi',
                                'count' => \App\Models\Course::where('status', 'published')->count(),
                                'color' => 'bg-brand-500',
                            ],
                            [
                                'label' => 'Draft',
                                'count' => \App\Models\Course::where('status', 'draft')->count(),
                                'color' => 'bg-amber-400',
                            ],
                            [
                                'label' => 'Diarsipkan',
                                'count' => \App\Models\Course::where('status', 'archived')->count(),
                                'color' => 'bg-slate-300 dark:bg-slate-600',
                            ],
                        ];
                    @endphp

                    @foreach ($overview as $item)
                        @php
                            $pct = round(($item['count'] / $total) * 100);
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2.5 text-sm">
                                <span class="text-slate-600 dark:text-slate-300 font-medium">{{ $item['label'] }}</span>
                                <span class="text-slate-400 text-xs font-medium">{{ number_format($item['count']) }}
                                    ({{ $pct }}%)
                                </span>
                            </div>
                            <div class="h-2 bg-slate-100 dark:bg-navy-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full progress-fill {{ $item['color'] }}"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Aksi Cepat --}}
            <div class="ep-card">
                <div class="ep-card-header pb-4 border-b border-slate-100 dark:border-navy-700/50 mb-3">
                    <h3 class="font-semibold text-slate-800 dark:text-slate-100 text-lg">Aksi Cepat</h3>
                </div>
                <div class="ep-card-body space-y-2.5">
                    @php
                        $quickLinks = [
                            [
                                'label' => 'Tambah Kursus Baru',
                                'href' => route('admin.courses.create'),
                                'icon' => 'M12 4v16m8-8H4',
                                'color' => 'text-brand-500 bg-brand-50 dark:bg-brand-500/10',
                            ],
                            [
                                'label' => 'Kelola Semua Kursus',
                                'href' => route('admin.courses.index'),
                                'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
                                'color' => 'text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10',
                            ],
                            [
                                'label' => 'Lihat Draft',
                                'href' => route('admin.courses.index', ['status' => 'draft']),
                                'icon' =>
                                    'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                                'color' => 'text-amber-500 bg-amber-50 dark:bg-amber-500/10',
                            ],
                        ];
                    @endphp

                    @foreach ($quickLinks as $link)
                        <a href="{{ $link['href'] }}"
                            class="flex items-center gap-3.5 p-3.5 rounded-xl hover:bg-slate-50 dark:hover:bg-navy-800/60 transition-colors group outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                            <div
                                class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ $link['color'] }}">
                                <svg class="w-4 h-4" aria-hidden="true" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $link['icon'] }}" />
                                </svg>
                            </div>
                            <span
                                class="text-sm font-medium text-slate-700 dark:text-slate-200 group-hover:text-brand-500 transition-colors">
                                {{ $link['label'] }}
                            </span>
                            <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 ml-auto group-hover:text-brand-400 transition-colors"
                                aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>

        </aside>
    </div>

@endsection
