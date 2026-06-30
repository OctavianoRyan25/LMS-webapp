@extends('layouts.app')
@section('title', 'Manajemen Ujian')

@section('content')

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-7">
        <div>
            <h2 class="font-display font-bold text-2xl text-slate-800 dark:text-white">Manajemen Ujian</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Semua ujian dari seluruh kursus
            </p>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="ep-btn-secondary flex-shrink-0">
            ← Kembali ke Kursus
        </a>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500">Total Ujian</p>
                </div>
            </div>
        </div>

        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['active'] }}</p>
                    <p class="text-xs text-slate-500">Ujian Aktif</p>
                </div>
            </div>
        </div>

        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['submissions'] }}</p>
                    <p class="text-xs text-slate-500">Total Pengerjaan</p>
                </div>
            </div>
        </div>

        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['avg_score'] }}</p>
                    <p class="text-xs text-slate-500">Rata-rata Skor</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Tabel ── --}}
    <x-table :columns="[
        ['key' => 'title', 'label' => 'Ujian', 'sortable' => true],
        ['key' => 'course', 'label' => 'Kursus', 'sortable' => false],
        ['key' => 'period', 'label' => 'Periode', 'sortable' => false],
        ['key' => 'questions', 'label' => 'Soal', 'sortable' => false],
        ['key' => 'passing_score', 'label' => 'Kelulusan', 'sortable' => true],
        ['key' => 'submissions', 'label' => 'Peserta', 'sortable' => false],
        ['key' => 'status', 'label' => 'Status', 'sortable' => false],
        ['key' => 'actions', 'label' => 'Aksi', 'sortable' => false],
    ]" :searchable="true">

        @forelse($exams as $exam)
            @php
                $total    = $exam->submissions_count ?? 0;
                $passed   = $exam->passed_count ?? 0;
                $passRate = $total > 0 ? round(($passed / $total) * 100) : 0;
                $course   = $exam->course;
                $isActive = $exam->isActive();
            @endphp
            <tr>

                {{-- Judul --}}
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-slate-800 dark:text-slate-100 leading-tight truncate max-w-[180px]">
                                {{ $exam->title }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">⏱ {{ $exam->duration_minutes }} menit</p>
                        </div>
                    </div>
                </td>

                {{-- Kursus --}}
                <td>
                    @if ($course)
                        <a href="{{ route('admin.courses.show', $course->id) }}"
                            class="text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-brand-500 transition-colors truncate max-w-[160px] block">
                            {{ $course->title }}
                        </a>
                    @else
                        <span class="text-sm text-slate-400 italic">–</span>
                    @endif
                </td>

                {{-- Periode --}}
                <td>
                    <div class="text-xs space-y-0.5">
                        @if ($exam->start_date)
                            <p class="text-slate-600 dark:text-slate-300">
                                <span class="text-slate-400">Mulai:</span>
                                {{ $exam->start_date->format('d M Y') }}
                            </p>
                        @endif
                        @if ($exam->end_date)
                            <p class="{{ $exam->end_date->isPast() ? 'text-red-400' : 'text-slate-600 dark:text-slate-300' }}">
                                <span class="text-slate-400">Selesai:</span>
                                {{ $exam->end_date->format('d M Y') }}
                            </p>
                        @endif
                        @if (!$exam->start_date && !$exam->end_date)
                            <span class="text-slate-400 italic">Tidak dibatasi</span>
                        @endif
                    </div>
                </td>

                {{-- Soal --}}
                <td>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                        {{ count($exam->questions) }}
                    </span>
                    <span class="text-xs text-slate-400 ml-0.5">soal</span>
                </td>

                {{-- Nilai Kelulusan --}}
                <td>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                        {{ $exam->passing_score }}%
                    </span>
                </td>

                {{-- Peserta --}}
                <td>
                    @if ($total === 0)
                        <span class="text-xs text-slate-400">Belum ada</span>
                    @else
                        <div class="space-y-1 min-w-[90px]">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $total }}</span>
                                <span class="text-xs {{ $passRate >= 70 ? 'text-emerald-500' : 'text-red-400' }}">
                                    {{ $passRate }}% lulus
                                </span>
                            </div>
                            <div class="h-1.5 bg-slate-100 dark:bg-navy-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full progress-fill {{ $passRate >= 70 ? 'bg-emerald-500' : 'bg-red-400' }}"
                                    style="width: {{ $passRate }}%"></div>
                            </div>
                        </div>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    @if ($isActive)
                        <x-badge color="green" :dot="true">Aktif</x-badge>
                    @elseif($exam->start_date && $exam->start_date->isFuture())
                        <x-badge color="yellow" :dot="true">Akan Datang</x-badge>
                    @else
                        <x-badge color="gray" :dot="true">Tidak Aktif</x-badge>
                    @endif
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('admin.exams.analytics', $exam) }}"
                            class="ep-btn-icon ep-btn-secondary ep-btn-sm" title="Analitik">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.exams.edit', $exam) }}"
                            class="ep-btn-icon ep-btn-outline ep-btn-sm" title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <button
                            onclick="EP.deleteForm(this, 'Ujian \'{{ addslashes($exam->title) }}\' dan semua hasil siswa akan dihapus permanen.')"
                            data-form-id="delete-exam-{{ $exam->id }}"
                            class="ep-btn-icon ep-btn-danger ep-btn-sm" title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        <form id="delete-exam-{{ $exam->id }}"
                            action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-14">
                    <div class="flex flex-col items-center gap-3 text-slate-400">
                        <svg class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="font-medium">Belum ada ujian</p>
                    </div>
                </td>
            </tr>
        @endforelse

        <x-slot:footer>
            <div class="flex items-center justify-between text-sm text-slate-500">
                <span>
                    Menampilkan {{ $exams->firstItem() ?? 0 }}–{{ $exams->lastItem() ?? 0 }}
                    dari {{ $exams->total() }} ujian
                </span>
                <div class="flex gap-1">
                    @if ($exams->onFirstPage())
                        <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 text-slate-400 cursor-not-allowed">←</span>
                    @else
                        <a href="{{ $exams->previousPageUrl() }}"
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">←</a>
                    @endif
                    @foreach ($exams->getUrlRange(1, $exams->lastPage()) as $page => $url)
                        @if ($page == $exams->currentPage())
                            <span class="px-3 py-1 rounded-lg bg-brand-500 text-white font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if ($exams->hasMorePages())
                        <a href="{{ $exams->nextPageUrl() }}"
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">→</a>
                    @else
                        <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 text-slate-400 cursor-not-allowed">→</span>
                    @endif
                </div>
            </div>
        </x-slot:footer>

    </x-table>

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
@endpush
