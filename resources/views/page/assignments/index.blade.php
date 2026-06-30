{{--
|--------------------------------------------------------------------------
| pages/assignments/index.blade.php — Semua Tugas (global)
|--------------------------------------------------------------------------
| Controller:
|
|   use App\Models\Assignment;
|   use App\Models\AssignmentSubmission;
|
|   public function index(): View
|   {
|       $assignments = Assignment::with(['lesson.course'])
|           ->withCount('submissions')
|           ->withCount(['submissions as graded_count' => fn($q) => $q->whereNotNull('score')])
|           ->latest()
|           ->paginate(15);
|
|       return view('page.assignments.index', [
|           'activeNav'   => 'courses',
|           'assignments' => $assignments,
|           'stats' => [
|               'total'   => Assignment::count(),
|               'graded'  => AssignmentSubmission::whereNotNull('score')->count(),
|               'pending' => AssignmentSubmission::whereNull('score')->count(),
|               'avg'     => round(AssignmentSubmission::whereNotNull('score')->avg('score') ?? 0, 1),
|           ],
|       ]);
|   }
--}}

@extends('layouts.app')
@section('title', 'Manajemen Tugas')

@section('content')

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-7">
        <div>
            <h2 class="font-display font-bold text-2xl text-slate-800 dark:text-white">
                Manajemen Tugas
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                Semua tugas dari seluruh kursus & lesson
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.courses.index') }}" class="ep-btn-secondary">
                ← Kembali ke Kursus
            </a>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['total'] }}</p>
                    <p class="text-xs text-slate-500">Total Tugas</p>
                </div>
            </div>
        </div>

        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['graded'] }}</p>
                    <p class="text-xs text-slate-500">Sudah Dinilai</p>
                </div>
            </div>
        </div>

        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['pending'] }}</p>
                    <p class="text-xs text-slate-500">Menunggu Nilai</p>
                </div>
            </div>
        </div>

        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-display font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $stats['avg'] }}</p>
                    <p class="text-xs text-slate-500">Rata-rata Nilai</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Tabel ── --}}
    <x-table :columns="[
        ['key' => 'title', 'label' => 'Tugas', 'sortable' => true],
        ['key' => 'course', 'label' => 'Kursus / Materi', 'sortable' => false],
        ['key' => 'due_date', 'label' => 'Batas Waktu', 'sortable' => true],
        ['key' => 'max_score', 'label' => 'Nilai Maks.', 'sortable' => true],
        ['key' => 'submissions', 'label' => 'Pengumpulan', 'sortable' => false],
        ['key' => 'graded', 'label' => 'Status Penilaian', 'sortable' => false],
        ['key' => 'actions', 'label' => 'Aksi', 'sortable' => false],
    ]" :searchable="true">

        @forelse($assignments as $assignment)
            @php
                $total = $assignment->submissions_count ?? 0;
                $graded = $assignment->graded_count ?? 0;
                $pending = $total - $graded;
                $pct = $total > 0 ? round(($graded / $total) * 100) : 0;
                $isOverdue = $assignment->due_date && $assignment->due_date->isPast();
                $lesson = $assignment->lesson;
                $course = $lesson?->course;
            @endphp
            <tr>

                {{-- Judul Tugas --}}
                <td>
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-500/10
                                flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-slate-800 dark:text-slate-100 leading-tight truncate max-w-[180px]">
                                {{ $assignment->title }}
                            </p>
                            @if ($assignment->description)
                                <p class="text-xs text-slate-400 truncate max-w-[180px] mt-0.5">
                                    {{ $assignment->description }}
                                </p>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- Kursus / Lesson --}}
                <td>
                    <div class="space-y-0.5 min-w-0">
                        @if ($course)
                            <a href="{{ route('admin.courses.show', $course->id) }}"
                                class="text-sm font-medium text-slate-700 dark:text-slate-200
                                  hover:text-brand-500 transition-colors truncate max-w-[160px] block">
                                {{ $course->title }}
                            </a>
                        @else
                            <span class="text-sm text-slate-400 italic">–</span>
                        @endif
                        @if ($lesson)
                            <a href="{{ route('admin.lessons.show', $lesson->id) }}"
                                class="text-xs text-slate-400 hover:text-brand-400 transition-colors truncate max-w-[160px] block">
                                {{ $lesson->title }}
                            </a>
                        @endif
                    </div>
                </td>

                {{-- Batas Waktu --}}
                <td>
                    @if ($assignment->due_date)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 {{ $isOverdue ? 'text-red-400' : 'text-slate-400' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if ($isOverdue)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                @endif
                            </svg>
                            <div>
                                <p
                                    class="text-sm {{ $isOverdue ? 'text-red-500 font-medium' : 'text-slate-700 dark:text-slate-200' }}">
                                    {{ $assignment->due_date->format('d M Y') }}
                                </p>
                                <p class="text-xs {{ $isOverdue ? 'text-red-400' : 'text-slate-400' }}">
                                    {{ $isOverdue
                                        ? 'Lewat ' . $assignment->due_date->diffForHumans()
                                        : $assignment->due_date->format('H:i') . ' WIB' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <span class="text-xs text-slate-400 italic">Tidak ada batas</span>
                    @endif
                </td>

                {{-- Nilai Maksimal --}}
                <td>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">
                        {{ $assignment->max_score ?? 100 }}
                    </span>
                    <span class="text-xs text-slate-400 ml-0.5">poin</span>
                </td>

                {{-- Jumlah Pengumpulan --}}
                <td>
                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $total }}</span>
                    <span class="text-xs text-slate-400 ml-1">pengumpulan</span>
                </td>

                {{-- Status Penilaian --}}
                <td>
                    @if ($total === 0)
                        <x-badge color="gray">Belum ada</x-badge>
                    @elseif($pending === 0)
                        <x-badge color="green" :dot="true">Semua dinilai</x-badge>
                    @else
                        <div class="space-y-1 min-w-[110px]">
                            <div class="flex items-center justify-between">
                                <x-badge color="yellow" :dot="true">{{ $pending }} pending</x-badge>
                                <span class="text-xs text-slate-400 ml-2">{{ $pct }}%</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 dark:bg-navy-700 rounded-full overflow-hidden">
                                <div class="h-full bg-brand-500 rounded-full progress-fill"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endif
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="flex items-center gap-1">

                        <a href="{{ route('admin.assignments.analytics', $assignment) }}"
                            class="ep-btn-icon ep-btn-secondary ep-btn-sm" title="Analitik">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>

                        <a href="{{ route('admin.assignments.edit', $assignment) }}"
                            class="ep-btn-icon ep-btn-outline ep-btn-sm" title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>

                        <button
                            onclick="EP.deleteForm(this, 'Tugas \'{{ addslashes($assignment->title) }}\' dan semua pengumpulannya akan dihapus permanen.')"
                            data-form-id="delete-assignment-{{ $assignment->id }}"
                            class="ep-btn-icon ep-btn-danger ep-btn-sm" title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        <form id="delete-assignment-{{ $assignment->id }}"
                            action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST"
                            class="hidden">
                            @csrf @method('DELETE')
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
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="font-medium">Belum ada tugas</p>
                    </div>
                </td>
            </tr>
        @endforelse

        <x-slot:footer>
            <div class="flex items-center justify-between text-sm text-slate-500">
                <span>
                    Menampilkan {{ $assignments->firstItem() ?? 0 }}–{{ $assignments->lastItem() ?? 0 }}
                    dari {{ $assignments->total() }} tugas
                </span>
                <div class="flex gap-1">
                    @if ($assignments->onFirstPage())
                        <span
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 text-slate-400 cursor-not-allowed">←</span>
                    @else
                        <a href="{{ $assignments->previousPageUrl() }}"
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">←</a>
                    @endif

                    @foreach ($assignments->getUrlRange(1, $assignments->lastPage()) as $page => $url)
                        @if ($page == $assignments->currentPage())
                            <span
                                class="px-3 py-1 rounded-lg bg-brand-500 text-white font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($assignments->hasMorePages())
                        <a href="{{ $assignments->nextPageUrl() }}"
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
