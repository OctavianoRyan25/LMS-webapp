@extends('layouts.app')
@section('title', isset($assignment) ? 'Edit Tugas' : 'Buat Tugas')

@section('content')

    @php
        $isEdit = isset($assignment);
        $assignment = $assignment ?? null;
        $formRoute = $isEdit
            ? route('admin.assignments.update', $assignment->id)
            : route('admin.lessons.assignments.store', $lesson->id);
    @endphp

    <x-page-header :title="$isEdit ? 'Edit Tugas' : 'Buat Tugas Baru'" :subtitle="'Lesson: ' . $lesson->title">
        <x-slot:actions>
            <a href="{{ route('admin.lessons.edit', $lesson) }}" class="ep-btn-secondary">← Kembali</a>
            @if ($isEdit)
                <a href="{{ route('admin.assignments.analytics', $assignment) }}" class="ep-btn-outline">
                    📊 Lihat Submissions
                </a>
            @endif
        </x-slot:actions>
    </x-page-header>

    <form action="{{ $formRoute }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Kolom Kiri --}}
            <div class="xl:col-span-2 space-y-5">
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Detail Tugas</h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        <x-form.input name="title" label="Judul Tugas" :required="true"
                            placeholder="Contoh: Tugas Praktik — Membuat Variabel" :value="$assignment->title ?? null" />

                        <x-form.textarea name="description" label="Deskripsi" :rows="2"
                            placeholder="Gambaran singkat tentang tugas ini..." :value="$assignment->description ?? null" />

                        <div>
                            <label for="instructions" class="ep-label">
                                Petunjuk Pengerjaan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="instructions" name="instructions" rows="6"
                                class="ep-input resize-y @error('instructions') ep-input-error @enderror"
                                placeholder="Jelaskan secara rinci langkah-langkah pengerjaan tugas, format jawaban, dll.">{{ old('instructions', $assignment->instructions ?? '') }}</textarea>
                            @error('instructions')
                                <p class="ep-error-msg">{{ $message }}</p>
                            @enderror
                            <p class="ep-hint">Petunjuk ini akan dilihat oleh siswa saat mengerjakan tugas.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="due_date" class="ep-label">Deadline</label>
                                <input type="datetime-local" id="due_date" name="due_date"
                                    class="ep-input @error('due_date') ep-input-error @enderror"
                                    value="{{ old('due_date', $assignment?->due_date?->format('Y-m-d\TH:i') ?? '') }}">
                                @error('due_date')
                                    <p class="ep-error-msg">{{ $message }}</p>
                                @enderror
                                <p class="ep-hint">Kosongkan jika tidak ada batas waktu.</p>
                            </div>
                            <x-form.input name="max_score" label="Nilai Maksimal" type="number" suffix="poin"
                                placeholder="100" :value="$assignment->max_score ?? 100" />
                        </div>

                    </div>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-5">

                {{-- Info --}}
                <div class="ep-card ep-card-body">
                    <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-1">Lesson</p>
                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $lesson->title }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $lesson->course->title }}</p>
                </div>

                {{-- Catatan penilaian --}}
                <div class="ep-card ep-card-body">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-brand-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-200">Penilaian Manual</p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Jawaban siswa berupa teks/esai. Anda dapat memberi nilai dan komentar dari halaman analitik.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="ep-card ep-card-body space-y-3">
                    <button type="submit" class="ep-btn-primary w-full" :disabled="loading">
                        <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span
                            x-text="loading ? 'Menyimpan...' : '{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Tugas' }}'"></span>
                    </button>
                    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="ep-btn-secondary w-full text-center block">
                        Batalkan
                    </a>
                    @if ($isEdit)
                        <hr class="border-slate-100 dark:border-navy-700">
                        <button type="button" class="ep-btn-danger w-full"
                            onclick="EP.deleteForm(this, 'Tugas dan semua submission siswa akan dihapus.')"
                            data-form-id="delete-assignment-form">
                            Hapus Tugas
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </form>

    @if ($isEdit)
        <form id="delete-assignment-form" action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST"
            class="hidden">
            @csrf @method('DELETE')
        </form>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
@endpush
