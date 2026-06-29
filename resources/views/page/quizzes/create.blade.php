@extends('layouts.app')
@section('title', isset($quiz) ? 'Edit Kuis' : 'Buat Kuis')

@section('content')

    @php
        $isEdit = isset($quiz);
        $formRoute = $isEdit
            ? route('admin.quizzes.update', $quiz->id)
            : route('admin.lessons.quizzes.store', $lesson->id);

        // Siapkan data soal untuk Alpine.js
        $existingQuestions = $isEdit ? $quiz->questions : [];
    @endphp

    <x-page-header :title="$isEdit ? 'Edit Kuis' : 'Buat Kuis Baru'" :subtitle="'Lesson: ' . $lesson->title">
        <x-slot:actions>
            <a href="{{ route('admin.lessons.edit', $lesson) }}" class="ep-btn-secondary">← Kembali</a>
            @if ($isEdit)
                <a href="{{ route('admin.quizzes.analytics', $quiz) }}" class="ep-btn-outline">
                    📊 Lihat Analitik
                </a>
            @endif
        </x-slot:actions>
    </x-page-header>

    <form action="{{ $formRoute }}" method="POST" x-data="quizBuilder()" @submit="loading = true">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Kolom Kiri: Soal-soal --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Info kuis --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Informasi Kuis</h3>
                    </div>
                    <div class="ep-card-body space-y-4">
                        <x-form.input name="title" label="Judul Kuis" :required="true"
                            placeholder="Contoh: Kuis Bab 1 — Pengenalan" :value="$quiz->title ?? null" />
                        <x-form.textarea name="description" label="Deskripsi" :rows="2"
                            placeholder="Opsional — jelaskan tujuan kuis ini..." :value="$quiz->description ?? null" />
                        <div class="grid grid-cols-2 gap-4">
                            <x-form.input name="duration_minutes" label="Durasi (Menit)" type="number" suffix="menit"
                                placeholder="30" :value="$quiz->duration_minutes ?? null" />
                            <x-form.input name="passing_score" label="Nilai Kelulusan" type="number" suffix="%"
                                placeholder="70" :value="$quiz->passing_score ?? 70" />
                        </div>
                    </div>
                </div>

                {{-- Builder Soal --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">
                            Soal-soal
                            <span class="ml-2 text-xs font-normal text-slate-400"
                                x-text="`(${questions.length} soal)`"></span>
                        </h3>
                        <button type="button" @click="addQuestion()" class="ep-btn-outline ep-btn-sm">
                            + Tambah Soal
                        </button>
                    </div>
                    <div class="ep-card-body space-y-4">

                        @error('q_text')
                            <div class="p-3 bg-red-50 dark:bg-red-500/10 rounded-xl text-sm text-red-600 dark:text-red-400">
                                {{ $message }}
                            </div>
                        @enderror

                        <template x-if="questions.length === 0">
                            <div class="text-center py-10 text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm">Belum ada soal. Klik "Tambah Soal" untuk mulai.</p>
                            </div>
                        </template>

                        <template x-for="(q, qi) in questions" :key="qi">
                            <div class="border border-slate-200 dark:border-navy-600 rounded-xl overflow-hidden">

                                {{-- Header soal --}}
                                <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 dark:bg-navy-800/60">
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-300"
                                        x-text="`Soal ${qi + 1}`"></span>
                                    <div class="flex-1"></div>
                                    <button type="button" @click="removeQuestion(qi)"
                                        class="text-red-400 hover:text-red-600 transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="p-4 space-y-4">
                                    {{-- Input pertanyaan --}}
                                    <div>
                                        <label class="ep-label">Pertanyaan</label>
                                        <textarea :name="`q_text[${qi}]`" rows="2" class="ep-input resize-y" placeholder="Tulis pertanyaan di sini..."
                                            x-model="q.question"></textarea>
                                    </div>

                                    {{-- Pilihan jawaban --}}
                                    <div>
                                        <label class="ep-label">
                                            Pilihan Jawaban
                                            <span class="text-xs font-normal text-slate-400 ml-1">
                                                (beri tanda ⦿ pada jawaban benar)
                                            </span>
                                        </label>
                                        <div class="space-y-2">
                                            <template x-for="(opt, oi) in q.options" :key="oi">
                                                <div class="flex items-center gap-2">
                                                    {{-- Radio benar/salah --}}
                                                    <input type="radio" :name="`q_correct[${qi}]`" :value="oi"
                                                        :checked="q.correct === oi" @change="q.correct = oi"
                                                        class="w-4 h-4 text-brand-500 focus:ring-brand-500">
                                                    {{-- Teks opsi --}}
                                                    <input type="text" :name="`q_options[${qi}][]`"
                                                        x-model="q.options[oi]"
                                                        :placeholder="`Opsi ${String.fromCharCode(65+oi)}`"
                                                        class="ep-input flex-1 !py-2">
                                                    {{-- Hapus opsi --}}
                                                    <button type="button" @click="removeOption(qi, oi)"
                                                        x-show="q.options.length > 2"
                                                        class="text-slate-400 hover:text-red-500 transition-colors p-1">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <button type="button" @click="addOption(qi)" x-show="q.options.length < 6"
                                                class="text-xs text-brand-500 hover:text-brand-600 transition-colors flex items-center gap-1 mt-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Tambah pilihan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                    <div class="px-5 pb-5" x-show="questions.length > 0">
                        <button type="button" @click="addQuestion()" class="ep-btn-outline w-full">
                            + Tambah Soal Lagi
                        </button>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-5">

                {{-- Info Lesson --}}
                <div class="ep-card">
                    <div class="ep-card-body">
                        <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-1">Lesson</p>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $lesson->title }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $lesson->course->title }}</p>
                    </div>
                </div>

                {{-- Ringkasan soal --}}
                <div class="ep-card" x-show="questions.length > 0">
                    <div class="ep-card-body">
                        <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-3">Ringkasan</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Total soal</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-100"
                                    x-text="questions.length + ' soal'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Soal lengkap</span>
                                <span class="font-semibold text-emerald-600" x-text="validQuestions + ' soal'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="ep-card">
                    <div class="ep-card-body space-y-3">
                        <button type="submit" class="ep-btn-primary w-full" :disabled="loading">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            <span
                                x-text="loading ? 'Menyimpan...' : '{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Kuis' }}'"></span>
                        </button>
                        <a href="{{ route('admin.lessons.edit', $lesson) }}"
                            class="ep-btn-secondary w-full text-center block">
                            Batalkan
                        </a>
                        @if ($isEdit)
                            <hr class="border-slate-100 dark:border-navy-700">
                            <button type="button" class="ep-btn-danger w-full"
                                onclick="EP.deleteForm(this, 'Kuis dan semua data jawaban siswa akan dihapus.')"
                                data-form-id="delete-quiz-form">
                                Hapus Kuis
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </form>

    @if (isset($quiz))
        <form id="delete-quiz-form" action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script>
        function quizBuilder() {
            return {
                loading: false,
                questions: @json($existingQuestions ?: []),

                get validQuestions() {
                    return this.questions.filter(q =>
                        q.question.trim() !== '' && q.options.filter(o => o.trim() !== '').length >= 2
                    ).length;
                },

                addQuestion() {
                    this.questions.push({
                        question: '',
                        options: ['', '', '', ''],
                        correct: 0,
                    });
                    this.$nextTick(() => {
                        const els = document.querySelectorAll('[placeholder="Tulis pertanyaan di sini..."]');
                        els[els.length - 1]?.focus();
                    });
                },

                removeQuestion(index) {
                    this.questions.splice(index, 1);
                },

                addOption(qi) {
                    this.questions[qi].options.push('');
                },

                removeOption(qi, oi) {
                    this.questions[qi].options.splice(oi, 1);
                    if (this.questions[qi].correct >= this.questions[qi].options.length) {
                        this.questions[qi].correct = 0;
                    }
                },
            };
        }
    </script>
@endpush
