@extends('layouts.app')
@section('title', 'Analitik Tugas — ' . $assignment->title)

@section('content')

<x-page-header :title="'Analitik: ' . $assignment->title"
    :subtitle="'Lesson: ' . $assignment->lesson->title">
    <x-slot:actions>
        <a href="{{ route('admin.assignments.edit', $assignment) }}" class="ep-btn-secondary">← Edit Tugas</a>
    </x-slot:actions>
</x-page-header>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="ep-card ep-card-body text-center">
        <p class="text-3xl font-bold text-slate-800 dark:text-white">{{ $total }}</p>
        <p class="text-xs text-slate-500 mt-1">Total Pengumpulan</p>
    </div>
    <div class="ep-card ep-card-body text-center">
        <p class="text-3xl font-bold text-emerald-600">{{ $graded }}</p>
        <p class="text-xs text-slate-500 mt-1">Sudah Dinilai</p>
    </div>
    <div class="ep-card ep-card-body text-center">
        <p class="text-3xl font-bold text-amber-500">{{ $ungraded }}</p>
        <p class="text-xs text-slate-500 mt-1">Belum Dinilai</p>
    </div>
    <div class="ep-card ep-card-body text-center">
        <p class="text-3xl font-bold text-brand-600">{{ $avgScore }}</p>
        <p class="text-xs text-slate-500 mt-1">Rata-rata Nilai</p>
    </div>
</div>

{{-- Info Tugas --}}
<div class="ep-card mb-6">
    <div class="ep-card-header">
        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Detail Tugas</h3>
        @if($assignment->due_date)
            @php $isOverdue = $assignment->due_date->isPast(); @endphp
            <x-badge :color="$isOverdue ? 'red' : 'green'" :dot="true">
                Deadline: {{ $assignment->due_date->format('d M Y H:i') }}
            </x-badge>
        @endif
    </div>
    <div class="ep-card-body">
        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
            {{ $assignment->instructions }}
        </p>
        <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
            <span>Nilai Maks: <strong class="text-slate-600 dark:text-slate-300">{{ $assignment->max_score }}</strong></span>
        </div>
    </div>
</div>

{{-- Tabel Submissions + Form Nilai --}}
<div class="ep-card overflow-hidden">
    <div class="ep-card-header">
        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Daftar Pengumpulan</h3>
    </div>
    <div class="space-y-0 divide-y divide-slate-100 dark:divide-navy-700">
        @forelse($submissions as $sub)
            <div class="p-5" x-data="{ open: false }">
                <div class="flex items-start gap-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($sub->user->name) }}&background=6366F1&color=fff&size=80"
                        class="w-10 h-10 rounded-full flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $sub->user->name }}</p>
                            @if($sub->isGraded())
                                <x-badge color="green">Dinilai: {{ $sub->score }}/{{ $assignment->max_score }}</x-badge>
                            @else
                                <x-badge color="yellow" :dot="true">Belum dinilai</x-badge>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Dikumpulkan {{ $sub->submitted_at?->format('d M Y H:i') ?? '-' }}
                            @if($sub->graded_at)
                                · Dinilai {{ $sub->graded_at->format('d M Y H:i') }}
                            @endif
                        </p>
                        {{-- Preview jawaban --}}
                        <div class="mt-3 p-3 bg-slate-50 dark:bg-navy-800/60 rounded-xl">
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed"
                                :class="open ? '' : 'line-clamp-3'">
                                {{ $sub->content }}
                            </p>
                            @if(strlen($sub->content) > 200)
                                <button type="button" @click="open = !open"
                                    class="text-xs text-brand-500 hover:text-brand-600 mt-1 transition-colors"
                                    x-text="open ? 'Tampilkan lebih sedikit' : 'Lihat selengkapnya'">
                                </button>
                            @endif
                        </div>
                        @if($sub->feedback)
                            <div class="mt-2 p-3 bg-brand-50 dark:bg-brand-500/10 rounded-xl">
                                <p class="text-xs font-semibold text-brand-700 dark:text-brand-400 mb-1">Komentar Tutor:</p>
                                <p class="text-sm text-brand-700 dark:text-brand-300">{{ $sub->feedback }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Form Nilai --}}
                    <div class="flex-shrink-0 w-48" x-data="{ editing: {{ $sub->isGraded() ? 'false' : 'true' }} }">
                        <form action="{{ route('admin.assignment-submissions.grade', $sub) }}" method="POST"
                              class="space-y-2">
                            @csrf
                            <div>
                                <label class="ep-label text-xs">Nilai (0–{{ $assignment->max_score }})</label>
                                <input type="number" name="score" min="0" max="{{ $assignment->max_score }}"
                                    value="{{ $sub->score }}"
                                    class="ep-input !py-2 !text-sm">
                            </div>
                            <div>
                                <label class="ep-label text-xs">Komentar</label>
                                <textarea name="feedback" rows="2"
                                    class="ep-input !text-sm resize-none"
                                    placeholder="Opsional...">{{ $sub->feedback }}</textarea>
                            </div>
                            <button type="submit" class="ep-btn-primary w-full ep-btn-sm">
                                {{ $sub->isGraded() ? 'Perbarui Nilai' : 'Beri Nilai' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p>Belum ada siswa yang mengumpulkan tugas ini.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
@endpush
