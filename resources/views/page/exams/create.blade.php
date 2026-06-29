@extends('layouts.app')
@section('title', isset($exam) ? 'Edit Ujian' : 'Buat Ujian')

@section('content')

    @php
        $isEdit = isset($exam);
        $exam = $exam ?? null;
        $formRoute = $isEdit ? route('admin.exams.update', $exam->id) : route('admin.courses.exams.store', $course->id);
        $existingQuestions = $isEdit ? $exam->questions : [];
    @endphp

    <x-page-header :title="$isEdit ? 'Edit Ujian' : 'Buat Ujian Baru'" :subtitle="'Kursus: ' . $course->title">
        <x-slot:actions>
            <a href="{{ route('admin.courses.show', $course) }}" class="ep-btn-secondary">← Kembali</a>
            @if ($isEdit)
                <a href="{{ route('admin.exams.analytics', $exam) }}" class="ep-btn-outline">
                    📊 Lihat Analitik
                </a>
            @endif
        </x-slot:actions>
    </x-page-header>

    <form action="{{ $formRoute }}" method="POST" x-data="examBuilder()" @submit="loading = true">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Kolom Kiri: Soal --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Info Ujian --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Informasi Ujian</h3>
                    </div>
                    <div class="ep-card-body space-y-4">
                        <x-form.input name="title" label="Judul Ujian" :required="true"
                            placeholder="Contoh: Ujian Akhir Semester" :value="$exam->title ?? null" />
                        <x-form.textarea name="description" label="Deskripsi" :rows="2"
                            placeholder="Gambaran singkat ujian..." :value="$exam->description ?? null" />
                        <div class="grid grid-cols-2 gap-4">
                            <x-form.input name="duration_minutes" label="Durasi (Menit)" :required="true" type="number"
                                suffix="menit" placeholder="60" :value="$exam->duration_minutes ?? null" />
                            <x-form.input name="passing_score" label="Nilai Kelulusan" type="number" suffix="%"
                                placeholder="70" :value="$exam->passing_score ?? 70" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="ep-label">Tanggal Mulai</label>
                                <input type="datetime-local" id="start_date" name="start_date" class="ep-input"
                                    value="{{ old('start_date', $exam?->start_date?->format('Y-m-d\TH:i') ?? '') }}">
                                <p class="ep-hint">Kosongkan jika langsung aktif.</p>
                            </div>
                            <div>
                                <label for="end_date" class="ep-label">Tanggal Selesai</label>
                                <input type="datetime-local" id="end_date" name="end_date" class="ep-input"
                                    value="{{ old('end_date', $exam?->end_date?->format('Y-m-d\TH:i') ?? '') }}">
                                <p class="ep-hint">Kosongkan jika tidak ada batas.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Builder Soal (sama dengan Quiz) --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">
                            Soal Ujian
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
                                <p class="text-sm">Belum ada soal. Klik "Tambah Soal" untuk mulai.</p>
                            </div>
                        </template>

                        <template x-for="(q, qi) in questions" :key="qi">
                            <div class="border border-slate-200 dark:border-navy-600 rounded-xl overflow-hidden">
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
                                    <div>
                                        <label class="ep-label">Pertanyaan</label>
                                        <textarea :name="`q_text[${qi}]`" rows="2" class="ep-input resize-y" placeholder="Tulis soal ujian..."
                                            x-model="q.question"></textarea>
                                    </div>
                                    <div>
                                        <label class="ep-label">
                                            Pilihan Jawaban
                                            <span class="text-xs font-normal text-slate-400 ml-1">(⦿ = jawaban benar)</span>
                                        </label>
                                        <div class="space-y-2">
                                            <template x-for="(opt, oi) in q.options" :key="oi">
                                                <div class="flex items-center gap-2">
                                                    <input type="radio" :name="`q_correct[${qi}]`" :value="oi"
                                                        :checked="q.correct === oi" @change="q.correct = oi"
                                                        class="w-4 h-4 text-brand-500 focus:ring-brand-500">
                                                    <input type="text" :name="`q_options[${qi}][]`"
                                                        x-model="q.options[oi]"
                                                        :placeholder="`Opsi ${String.fromCharCode(65+oi)}`"
                                                        class="ep-input flex-1 !py-2">
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
                                                + Tambah pilihan
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

                <div class="ep-card ep-card-body">
                    <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-1">Kursus</p>
                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $course->title }}</p>
                </div>

                <div class="ep-card ep-card-body" x-show="questions.length > 0">
                    <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-3">Ringkasan</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total soal</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-100"
                                x-text="questions.length + ' soal'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Soal valid</span>
                            <span class="font-semibold text-emerald-600" x-text="validQuestions + ' soal'"></span>
                        </div>
                    </div>
                </div>

                <div class="ep-card ep-card-body space-y-3">
                    <button type="submit" class="ep-btn-primary w-full" :disabled="loading">
                        <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <span
                            x-text="loading ? 'Menyimpan...' : '{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Ujian' }}'"></span>
                    </button>
                    <a href="{{ route('admin.courses.show', $course) }}"
                        class="ep-btn-secondary w-full text-center block">
                        Batalkan
                    </a>
                    @if ($isEdit)
                        <hr class="border-slate-100 dark:border-navy-700">
                        <button type="button" class="ep-btn-danger w-full"
                            onclick="EP.deleteForm(this, 'Ujian dan semua hasil siswa akan dihapus.')"
                            data-form-id="delete-exam-form">
                            Hapus Ujian
                        </button>
                    @endif
                </div>
            </div>

        </div>
    </form>

    @if ($isEdit)
        <form id="delete-exam-form" action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script>
        function examBuilder() {
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
                        correct: 0
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
