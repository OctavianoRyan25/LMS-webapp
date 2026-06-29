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

            {{-- Card: Lessons --}}
            <div class="ep-card">
                <div class="ep-card-header">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">
                        Lessons ({{ $course->lessons->count() }})
                    </h3>
                    <a href="{{ route('admin.courses.lessons.create', $course) }}" class="ep-btn-outline ep-btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Lesson
                    </a>
                </div>
                <div class="ep-card-body divide-y divide-slate-100 dark:divide-navy-700">
                    @forelse($course->lessons as $lesson)
                        <div class="flex items-center gap-3 py-3 group">
                            {{-- Nomor urut --}}
                            <div
                                class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-semibold text-brand-600 dark:text-brand-400">
                                    {{ $lesson->order }}
                                </span>
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">
                                    {{ $lesson->title }}
                                </p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if ($lesson->duration_minutes)
                                        <span class="text-xs text-slate-400">{{ $lesson->duration_minutes }} menit</span>
                                    @endif
                                    @if ($lesson->is_free_preview)
                                        <x-badge color="blue" size="sm">Free Preview</x-badge>
                                    @endif
                                    @if ($lesson->video_url)
                                        <span class="text-xs text-slate-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm12.553 1.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                            </svg>
                                            Video
                                        </span>
                                    @endif
                                    @if ($lesson->files->count() > 0)
                                        <span class="text-xs text-slate-400 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-paperclip-icon lucide-paperclip">
                                                <path
                                                    d="m16 6-8.414 8.586a2 2 0 0 0 2.829 2.829l8.414-8.586a4 4 0 1 0-5.657-5.657l-8.379 8.551a6 6 0 1 0 8.485 8.485l8.379-8.551" />
                                            </svg>
                                            {{ $lesson->files->count() }} lampiran
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Aksi --}}
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.lessons.edit', $lesson) }}"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors"
                                    title="Edit Lesson">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button type="button"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                                    onclick="EP.deleteForm(this, 'Lesson ini beserta video dan lampirannya akan dihapus permanen.')"
                                    data-form-id="delete-lesson-{{ $lesson->id }}" title="Hapus Lesson">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p class="text-sm text-slate-400">Belum ada lesson</p>
                            <a href="{{ route('admin.courses.lessons.create', $course) }}"
                                class="ep-btn-outline ep-btn-sm mt-3 inline-flex">
                                + Tambah Lesson Pertama
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Card: Ujian --}}
            <div class="ep-card">
                <div class="ep-card-header">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">
                        Ujian ({{ $course->exams->count() }})
                    </h3>
                    <a href="{{ route('admin.courses.exams.create', $course) }}" class="ep-btn-outline ep-btn-sm">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Ujian
                    </a>
                </div>
                <div class="ep-card-body divide-y divide-slate-100 dark:divide-navy-700">
                    @forelse($course->exams as $exam)
                        <div class="flex items-center gap-3 py-3 group">
                            <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">
                                        {{ $exam->title }}
                                    </p>
                                    @if($exam->isActive())
                                        <x-badge color="green" size="sm">Aktif</x-badge>
                                    @else
                                        <x-badge color="gray" size="sm">Tidak Aktif</x-badge>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ count($exam->questions) }} soal
                                    · {{ $exam->duration_minutes }} menit
                                    · Lulus ≥{{ $exam->passing_score }}%
                                    @if($exam->submissions_count ?? $exam->submissions->count())
                                        · {{ $exam->submissions->count() }} peserta
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.exams.analytics', $exam) }}"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors"
                                    title="Analitik">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.exams.edit', $exam) }}"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button type="button"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                                    onclick="EP.deleteForm(this, 'Ujian dan semua hasil siswa akan dihapus permanen.')"
                                    data-form-id="delete-exam-{{ $exam->id }}"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-slate-400 mb-3">Belum ada ujian untuk kursus ini</p>
                            <a href="{{ route('admin.courses.exams.create', $course) }}"
                               class="ep-btn-outline ep-btn-sm">
                                + Tambah Ujian Pertama
                            </a>
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

{{-- !! Form hapus lesson — WAJIB di luar form lain !! --}}
@foreach ($course->lessons as $lesson)
    <form id="delete-lesson-{{ $lesson->id }}" action="{{ route('admin.lessons.destroy', $lesson) }}"
        method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>
@endforeach

{{-- Form hapus exam --}}
@foreach ($course->exams as $exam)
    <form id="delete-exam-{{ $exam->id }}" action="{{ route('admin.exams.destroy', $exam) }}"
        method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>
@endforeach

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
@endpush
