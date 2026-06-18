{{--
|--------------------------------------------------------------------------
| pages/courses/show.blade.php  —  Detail Kursus
|--------------------------------------------------------------------------
--}}

@extends('layouts.app')

@section('title', 'Detail Kursus')

@section('content')

    {{-- ── Page Header ── --}}
    <x-page-header :title="$course->title" subtitle="Detail informasi kursus">
        <x-slot:actions>
            <a href="{{ route('admin.courses.index') }}" class="ep-btn-secondary">
                ← Kembali
            </a>
            <a href="{{ route('admin.courses.edit', $course->id) }}" class="ep-btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Kursus
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ── Kolom Kiri (2/3) ── --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Card: Informasi Kursus --}}
            <div class="ep-card">
                <div class="ep-card-header">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">Informasi Kursus</h3>
                    @php
                        $statusMap = [
                            'draft' => ['color' => 'yellow', 'label' => 'Draf'],
                            'published' => ['color' => 'green', 'label' => 'Dipublikasi'],
                            'archived' => ['color' => 'gray', 'label' => 'Diarsipkan'],
                        ];
                        $st = $statusMap[$course->status] ?? ['color' => 'blue', 'label' => $course->status];
                    @endphp
                    <x-badge :color="$st['color']" :dot="true">{{ $st['label'] }}</x-badge>
                </div>
                <div class="ep-card-body space-y-4">

                    @if ($course->thumbnail)
                        <div class="mb-4">
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}"
                                class="w-full h-64 object-cover rounded-xl">
                        </div>
                    @endif

                    <div>
                        <label class="ep-label">Deskripsi</label>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $course->description }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="ep-label">Kategori</label>
                            <p class="text-sm text-slate-800 dark:text-slate-200">
                                {{ $course->category_id ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="ep-label">Level</label>
                            <p class="text-sm text-slate-800 dark:text-slate-200">
                                @if ($course->level == 'beginner')
                                    Pemula
                                @elseif($course->level == 'intermediate')
                                    Menengah
                                @elseif($course->level == 'advanced')
                                    Lanjutan
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="ep-label">Harga</label>
                            <p class="text-sm text-slate-800 dark:text-slate-200 font-semibold">
                                @if ($course->price > 0)
                                    Rp {{ number_format($course->price, 0, ',', '.') }}
                                @else
                                    <span class="text-emerald-600">Gratis</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="ep-label">Durasi</label>
                            <p class="text-sm text-slate-800 dark:text-slate-200">
                                {{ $course->duration_hours ?? 0 }} jam
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="ep-label">Instruktur</label>
                        <div class="flex items-center gap-3 mt-1">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor_id ?? 'Unknown') }}&size=40&background=6366F1&color=fff"
                                class="w-10 h-10 rounded-full" alt="">
                            <div>
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                    {{ $course->instructor_id ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-navy-800/60 rounded-xl">
                        @if ($course->has_certificate)
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                Kursus ini menyediakan sertifikat otomatis
                            </span>
                        @else
                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="text-sm text-slate-500">
                                Tidak ada sertifikat untuk kursus ini
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Card: Chapters --}}
            <div class="ep-card">
                <div class="ep-card-header">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">
                        Chapters ({{ $course->chapters->count() }})
                    </h3>
                    <a href="#" class="ep-btn-outline ep-btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Chapter
                    </a>
                </div>
                <div class="ep-card-body">
                    @forelse($course->chapters as $chapter)
                        <div
                            class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-navy-800/40 transition-colors">
                            <div
                                class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-semibold text-brand-600 dark:text-brand-400">
                                    {{ $chapter->order }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-800 dark:text-slate-100">
                                    {{ $chapter->title }}
                                </p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $chapter->lessons->count() }} lessons
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-slate-400">Belum ada chapter</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ── Kolom Kanan (1/3) ── --}}
        <div class="space-y-5">

            {{-- Card: Metadata --}}
            <div class="ep-card">
                <div class="ep-card-header">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">Metadata</h3>
                </div>
                <div class="ep-card-body space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">ID:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">
                            #{{ str_pad($course->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Dibuat:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">
                            {{ $course->created_at->format('d M Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Diperbarui:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">
                            {{ $course->updated_at->format('d M Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Pembuat:</span>
                        <span class="font-medium text-slate-800 dark:text-slate-200">
                            {{ $course->creator->name }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card: Aksi --}}
            <div class="ep-card">
                <div class="ep-card-body space-y-3">
                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="ep-btn-primary w-full">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Kursus
                    </a>

                    <hr class="border-slate-100 dark:border-navy-700">

                    <button type="button" class="ep-btn-danger w-full"
                        onclick="EP.deleteForm(this, 'Kursus ini akan dihapus permanen beserta semua datanya.')"
                        data-form-id="delete-course-form">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Kursus
                    </button>

                    <form id="delete-course-form" action="{{ route('admin.courses.destroy', $course->id) }}"
                        method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
@endpush
