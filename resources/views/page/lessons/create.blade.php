{{--
|--------------------------------------------------------------------------
| page/lessons/create.blade.php  —  Form Tambah / Edit Lesson
|--------------------------------------------------------------------------
| Digunakan untuk Create dan Edit (shared view)
| Controller create: view('page.lessons.create', ['course' => $course])
| Controller edit:   view('page.lessons.create', ['course' => $course, 'lesson' => $lesson])
--}}

@extends('layouts.app')

@section('title', isset($lesson) ? 'Edit Lesson' : 'Tambah Lesson')

@section('content')

    @php
        $isEdit    = isset($lesson);
        $formTitle = $isEdit ? 'Edit Lesson' : 'Tambah Lesson Baru';
        $formRoute = $isEdit
            ? route('admin.lessons.update', $lesson->id)
            : route('admin.courses.lessons.store', $course->id);
    @endphp

    {{-- ── Page Header ── --}}
    <x-page-header :title="$formTitle" :subtitle="'Kursus: ' . $course->title">
        <x-slot:actions>
            <a href="{{ route('admin.courses.show', $course) }}" class="ep-btn-secondary">
                ← Kembali ke Kursus
            </a>
        </x-slot:actions>
    </x-page-header>

    {{-- ── Form ── --}}
    <form action="{{ $formRoute }}" method="POST" enctype="multipart/form-data"
          x-data="lessonForm()" @submit="loading = true">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ── Kolom Kiri (2/3) ── --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Card: Informasi Lesson --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Informasi Lesson</h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        <x-form.input name="title" label="Judul Lesson" :required="true"
                            placeholder="Contoh: Pengenalan Python"
                            :value="$lesson->title ?? null" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-form.input name="order" label="Urutan (Order)" type="number"
                                placeholder="1"
                                hint="Lesson diurutkan dari kecil ke besar."
                                :value="$lesson->order ?? ($course->lessons->max('order') + 1)" />

                            <x-form.input name="duration_minutes" label="Durasi (Menit)" type="number"
                                placeholder="10"
                                suffix="menit"
                                :value="$lesson->duration_minutes ?? null" />
                        </div>

                        <x-form.textarea name="content" label="Konten / Deskripsi"
                            placeholder="Tulis deskripsi atau konten teks lesson di sini..."
                            :rows="6"
                            :value="$lesson->content ?? null" />

                    </div>
                </div>

                {{-- Card: Upload Video --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Video Materi</h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        {{-- Preview video existing --}}
                        @if($isEdit && $lesson->video_url)
                            <div class="rounded-xl overflow-hidden bg-black">
                                <video controls class="w-full max-h-64">
                                    <source src="{{ $lesson->video_url }}" type="video/mp4">
                                    Browser tidak mendukung tag video.
                                </video>
                            </div>
                            <p class="text-xs text-slate-400">
                                Video saat ini sudah terupload. Upload video baru untuk mengganti.
                            </p>
                        @endif

                        {{-- Upload video baru --}}
                        <div x-data="{ fileName: '', fileSize: '' }">
                            <label class="ep-label">
                                {{ $isEdit && $lesson->video_url ? 'Ganti Video' : 'Upload Video' }}
                            </label>
                            <div class="mt-1 border-2 border-dashed border-slate-200 dark:border-navy-600 rounded-xl p-6 text-center
                                        hover:border-brand-400 transition-colors cursor-pointer"
                                 @click="$refs.videoInput.click()">
                                <template x-if="!fileName">
                                    <div>
                                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-sm text-slate-500">Klik atau drag & drop video</p>
                                        <p class="text-xs text-slate-400 mt-1">MP4, MOV, AVI, WebM — Maks. 200MB</p>
                                    </div>
                                </template>
                                <template x-if="fileName">
                                    <div class="flex items-center gap-3 justify-center">
                                        <svg class="w-8 h-8 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <div class="text-left">
                                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100" x-text="fileName"></p>
                                            <p class="text-xs text-slate-400" x-text="fileSize"></p>
                                        </div>
                                    </div>
                                </template>
                                <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm"
                                       class="hidden" x-ref="videoInput"
                                       @change="
                                           const f = $event.target.files[0];
                                           if(f) {
                                               fileName = f.name;
                                               fileSize = (f.size / 1024 / 1024).toFixed(1) + ' MB';
                                           }
                                       ">
                            </div>
                            @error('video')
                                <p class="ep-error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Card: Lampiran / Materi --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Lampiran & Materi</h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        {{-- List lampiran existing (hanya saat edit) --}}
                        @if($isEdit && $lesson->files->count() > 0)
                            <div class="space-y-2">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Lampiran Saat Ini</p>
                                @foreach($lesson->files->where('file_type', 'attachment') as $file)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-navy-800/60">
                                        <svg class="w-8 h-8 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">
                                                {{ $file->file_name }}
                                            </p>
                                            @if($file->file_size)
                                                <p class="text-xs text-slate-400">
                                                    {{ number_format($file->file_size / 1024, 0) }} KB
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ $file->file_url }}" target="_blank"
                                               class="ep-btn-outline ep-btn-sm">Unduh</a>
                                            <button type="button"
                                                class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                                                onclick="EP.deleteForm(this, 'Lampiran ini akan dihapus permanen.')"
                                                data-form-id="delete-file-{{ $file->id }}"
                                                title="Hapus lampiran">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Upload lampiran baru --}}
                        <div x-data="{ files: [] }">
                            <label class="ep-label">Tambah Lampiran Baru</label>
                            <div class="mt-1 border-2 border-dashed border-slate-200 dark:border-navy-600 rounded-xl p-5 text-center
                                        hover:border-brand-400 transition-colors cursor-pointer"
                                 @click="$refs.attachInput.click()">
                                <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <p class="text-sm text-slate-500">Klik untuk pilih file lampiran</p>
                                <p class="text-xs text-slate-400 mt-1">PDF, DOC, XLS, PPT, ZIP — Maks. 20MB per file</p>
                                <input type="file" name="attachments[]" multiple
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.txt"
                                       class="hidden" x-ref="attachInput"
                                       @change="files = Array.from($event.target.files).map(f => f.name)">
                            </div>
                            <template x-if="files.length > 0">
                                <ul class="mt-2 space-y-1">
                                    <template x-for="name in files" :key="name">
                                        <li class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-brand-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span x-text="name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                            @error('attachments.*')
                                <p class="ep-error-msg">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── Kolom Kanan (1/3) ── --}}
            <div class="space-y-5">

                {{-- Card: Pengaturan --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Pengaturan</h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        {{-- Toggle: Free Preview --}}
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-navy-800/60 rounded-xl"
                             x-data="{ on: {{ old('is_free_preview', $lesson->is_free_preview ?? false) ? 'true' : 'false' }} }">
                            <div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">Free Preview</p>
                                <p class="text-xs text-slate-400 mt-0.5">Dapat diakses tanpa mendaftar</p>
                            </div>
                            <div>
                                <input type="hidden" name="is_free_preview" :value="on ? '1' : '0'">
                                <button type="button" @click="on = !on"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500/50"
                                    :class="on ? 'bg-brand-500' : 'bg-slate-300 dark:bg-slate-600'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                          :class="on ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Card: Info Kursus --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Kursus</h3>
                    </div>
                    <div class="ep-card-body">
                        @if($course->thumbnail_url)
                            <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}"
                                 class="w-full h-24 object-cover rounded-lg mb-3">
                        @endif
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $course->title }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $course->lessons->count() }} lesson &middot; {{ $course->category_id ?? 'Umum' }}
                        </p>
                    </div>
                </div>

                {{-- Card: Tombol Aksi --}}
                <div class="ep-card">
                    <div class="ep-card-body space-y-3">

                        <button type="submit" class="ep-btn-primary w-full" :disabled="loading">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            <span x-text="loading ? 'Menyimpan...' : '{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Lesson' }}'"></span>
                        </button>

                        <a href="{{ route('admin.courses.show', $course) }}" class="ep-btn-secondary w-full text-center block">
                            Batalkan
                        </a>

                        @if($isEdit)
                            <hr class="border-slate-100 dark:border-navy-700">
                            <button type="button" class="ep-btn-danger w-full"
                                onclick="EP.deleteForm(this, 'Lesson ini beserta video dan semua lampirannya akan dihapus permanen.')"
                                data-form-id="delete-lesson-form">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Lesson Ini
                            </button>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </form>

    {{-- !! Form hapus di LUAR form utama !! --}}
    @if($isEdit)
        <form id="delete-lesson-form" action="{{ route('admin.lessons.destroy', $lesson) }}"
              method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>

        {{-- Form hapus per lampiran --}}
        @foreach($lesson->files as $file)
            <form id="delete-file-{{ $file->id }}"
                  action="{{ route('admin.lesson-files.destroy', $file) }}"
                  method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach

        {{-- ── Kuis & Tugas section (hanya saat edit) ── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            {{-- Card: Kuis --}}
            <div class="ep-card">
                <div class="ep-card-header">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">
                        Kuis ({{ $lesson->quizzes->count() }})
                    </h3>
                    <a href="{{ route('admin.lessons.quizzes.create', $lesson) }}" class="ep-btn-outline ep-btn-sm">
                        + Tambah Kuis
                    </a>
                </div>
                <div class="ep-card-body divide-y divide-slate-100 dark:divide-navy-700">
                    @forelse($lesson->quizzes as $quiz)
                        <div class="flex items-center gap-3 py-2.5 group">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">
                                    {{ $quiz->title }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ count($quiz->questions) }} soal · Lulus ≥{{ $quiz->passing_score }}%
                                    @if($quiz->duration_minutes) · {{ $quiz->duration_minutes }} menit @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.quizzes.analytics', $quiz) }}"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors"
                                    title="Analitik">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button type="button"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                                    onclick="EP.deleteForm(this, 'Kuis dan semua jawaban siswa akan dihapus.')"
                                    data-form-id="delete-quiz-{{ $quiz->id }}"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 py-4 text-center">Belum ada kuis</p>
                    @endforelse
                </div>
            </div>

            {{-- Card: Tugas --}}
            <div class="ep-card">
                <div class="ep-card-header">
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">
                        Tugas ({{ $lesson->assignments->count() }})
                    </h3>
                    <a href="{{ route('admin.lessons.assignments.create', $lesson) }}" class="ep-btn-outline ep-btn-sm">
                        + Tambah Tugas
                    </a>
                </div>
                <div class="ep-card-body divide-y divide-slate-100 dark:divide-navy-700">
                    @forelse($lesson->assignments as $assignment)
                        <div class="flex items-center gap-3 py-2.5 group">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">
                                    {{ $assignment->title }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    Maks. {{ $assignment->max_score }} poin
                                    @if($assignment->due_date)
                                        · Deadline {{ $assignment->due_date->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.assignments.analytics', $assignment) }}"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors"
                                    title="Submissions">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.assignments.edit', $assignment) }}"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button type="button"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors"
                                    onclick="EP.deleteForm(this, 'Tugas dan semua submission siswa akan dihapus.')"
                                    data-form-id="delete-assignment-{{ $assignment->id }}"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 py-4 text-center">Belum ada tugas</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Delete forms quiz & assignment --}}
        @foreach($lesson->quizzes as $quiz)
            <form id="delete-quiz-{{ $quiz->id }}"
                  action="{{ route('admin.quizzes.destroy', $quiz) }}"
                  method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach
        @foreach($lesson->assignments as $assignment)
            <form id="delete-assignment-{{ $assignment->id }}"
                  action="{{ route('admin.assignments.destroy', $assignment) }}"
                  method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        @endforeach
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script>
        function lessonForm() {
            return { loading: false }
        }
    </script>
@endpush
