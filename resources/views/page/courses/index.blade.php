{{--
|--------------------------------------------------------------------------
| pages/courses/index.blade.php  —  Contoh Halaman: Daftar Kursus
|--------------------------------------------------------------------------
| Controller:
|   return view('admin.courses.index', [
|       'activeNav' => 'courses',
|       'courses'   => Course::latest()->paginate(10),
|   ]);
--}}

@extends('layouts.app')

@section('title', 'Manajemen Kursus')

@section('content')

    {{-- ── Page Header ── --}}
    <x-page-header title="Manajemen Kursus" subtitle="Kelola semua kursus yang tersedia di platform">
        <x-slot:actions>
            <button onclick="EP.toast.info('Filter belum tersedia')" class="ep-btn-secondary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                Filter
            </button>
            <a href="{{ route('admin.courses.create') }}" class="ep-btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kursus
            </a>
        </x-slot:actions>
    </x-page-header>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <span class="text-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-book-open-text-icon lucide-book-open-text">
                        <path d="M12 7v14" />
                        <path d="M16 12h2" />
                        <path d="M16 8h2" />
                        <path
                            d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />
                        <path d="M6 12h2" />
                        <path d="M6 8h2" />
                    </svg>
                </span>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500">Total Kursus</p>
                </div>
            </div>
        </div>
        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <span class="text-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-book-open-check-icon lucide-book-open-check">
                        <path d="M12 21V7" />
                        <path d="m16 12 2 2 4-4" />
                        <path
                            d="M22 6V4a1 1 0 0 0-1-1h-5a4 4 0 0 0-4 4 4 4 0 0 0-4-4H3a1 1 0 0 0-1 1v13a1 1 0 0 0 1 1h6a3 3 0 0 1 3 3 3 3 0 0 1 3-3h6a1 1 0 0 0 1-1v-1.3" />
                    </svg>
                </span>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['published'] }}</p>
                    <p class="text-xs text-slate-500">Dipublikasi</p>
                </div>
            </div>
        </div>
        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <span class="text-2xl"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-book-alert-icon lucide-book-alert">
                        <path d="M12 13h.01" />
                        <path d="M12 6v3" />
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20" />
                    </svg></span>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['draft'] }}</p>
                    <p class="text-xs text-slate-500">Draf</p>
                </div>
            </div>
        </div>
        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <span class="text-2xl"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-archive-restore-icon lucide-archive-restore">
                        <rect width="20" height="5" x="2" y="3" rx="1" />
                        <path d="M4 8v11a2 2 0 0 0 2 2h2" />
                        <path d="M20 8v11a2 2 0 0 1-2 2h-2" />
                        <path d="m9 15 3-3 3 3" />
                        <path d="M12 12v9" />
                    </svg></span>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['archived'] }}</p>
                    <p class="text-xs text-slate-500">Diarsipkan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabel Kursus ── --}}
    <x-table :columns="[
        ['key' => 'name', 'label' => 'Kursus', 'sortable' => true],
        ['key' => 'category', 'label' => 'Kategori', 'sortable' => true],
        ['key' => 'instructor', 'label' => 'Instruktur', 'sortable' => true],
        ['key' => 'lesson_count', 'label' => 'Pelajaran', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'sortable' => false],
        ['key' => 'created_at', 'label' => 'Dibuat', 'sortable' => true],
        ['key' => 'actions', 'label' => 'Aksi', 'sortable' => false],
    ]" :searchable="true">

        {{-- Baris data --}}
        @forelse($courses as $course)
            <tr>
                {{-- Kursus --}}
                <td>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-base flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-book-open-icon lucide-book-open">
                                <path d="M12 7v14" />
                                <path
                                    d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800 dark:text-slate-100 leading-tight">
                                {{ $course->title }}
                            </p>
                            <p class="text-xs text-slate-400">ID: #{{ str_pad($course->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </td>

                {{-- Kategori --}}
                <td>
                    <x-badge color="blue">{{ $course->category_id ?? 'Umum' }}</x-badge>
                </td>

                {{-- Instruktur --}}
                <td>
                    <div class="flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($course->creator->name) }}&size=32&background=6366F1&color=fff"
                            class="w-6 h-6 rounded-full" alt="">
                        <span>{{ $course->creator->name }}</span>
                    </div>
                </td>

                {{-- Siswa --}}
                <td>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                        {{ $course->lessons->count() }}
                    </span>
                </td>

                {{-- Status Badge --}}
                <td>
                    @php
                        $statusMap = [
                            'published' => ['color' => 'green', 'label' => 'Dipublikasi'],
                            'draft' => ['color' => 'yellow', 'label' => 'Draf'],
                            'archived' => ['color' => 'gray', 'label' => 'Arsip'],
                        ];
                        $st = $statusMap[$course->status] ?? ['color' => 'blue', 'label' => $course->status];
                    @endphp
                    <x-badge :color="$st['color']" :dot="true">{{ $st['label'] }}</x-badge>
                </td>

                {{-- Tanggal --}}
                <td class="text-slate-400 text-xs">{{ $course->created_at->format('d M Y') }}</td>

                {{-- Aksi --}}
                <td>
                    <div class="flex items-center gap-1">

                        {{-- Lihat --}}
                        <a href="{{ route('admin.courses.show', $course->id) }}"
                            class="ep-btn-icon ep-btn-secondary ep-btn-sm" title="Lihat">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('admin.courses.edit', $course->id) }}"
                            class="ep-btn-icon ep-btn-outline ep-btn-sm" title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>

                        {{-- Hapus --}}
                        <button
                            onclick="EP.deleteForm(this, 'Kursus \'{{ addslashes($course->title) }}\' akan dihapus permanen.')"
                            data-form-id="delete-course-{{ $course->id }}" class="ep-btn-icon ep-btn-danger ep-btn-sm"
                            title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        {{-- Form hapus tersembunyi --}}
                        <form id="delete-course-{{ $course->id }}"
                            action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>

                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-14">
                    <div class="flex flex-col items-center gap-3 text-slate-400">
                        <svg class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <p class="font-medium">Belum ada kursus</p>
                        <a href="{{ route('admin.courses.create') }}" class="ep-btn-primary ep-btn-sm mt-1">
                            Buat kursus pertama
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse

        {{-- Pagination di footer --}}
        <x-slot:footer>
            <div class="flex items-center justify-between text-sm text-slate-500">
                <span>Menampilkan {{ $courses->firstItem() ?? 0 }}–{{ $courses->lastItem() ?? 0 }} dari
                    {{ $courses->total() }} kursus</span>
                <div class="flex gap-1">
                    @if ($courses->onFirstPage())
                        <span
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 text-slate-400 cursor-not-allowed">←</span>
                    @else
                        <a href="{{ $courses->previousPageUrl() }}"
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">←</a>
                    @endif

                    @foreach ($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
                        @if ($page == $courses->currentPage())
                            <span
                                class="px-3 py-1 rounded-lg bg-brand-500 text-white font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($courses->hasMorePages())
                        <a href="{{ $courses->nextPageUrl() }}"
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">→</a>
                    @else
                        <span
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 text-slate-400 cursor-not-allowed">→</span>
                    @endif
                </div>
            </div>
        </x-slot:footer>

    </x-table>

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
@endpush
