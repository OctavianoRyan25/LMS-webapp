@extends('layouts.app')
@section('title', 'Analitik Ujian — ' . $exam->title)

@section('content')

<x-page-header :title="'Analitik: ' . $exam->title"
    :subtitle="'Kursus: ' . $exam->course->title">
    <x-slot:actions>
        <a href="{{ route('admin.exams.edit', $exam) }}" class="ep-btn-secondary">← Edit Ujian</a>
    </x-slot:actions>
</x-page-header>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="ep-card ep-card-body text-center">
        <p class="text-3xl font-bold text-slate-800 dark:text-white">{{ $total }}</p>
        <p class="text-xs text-slate-500 mt-1">Total Peserta</p>
    </div>
    <div class="ep-card ep-card-body text-center">
        <p class="text-3xl font-bold text-emerald-600">{{ $passed }}</p>
        <p class="text-xs text-slate-500 mt-1">Lulus</p>
    </div>
    <div class="ep-card ep-card-body text-center">
        <p class="text-3xl font-bold text-red-500">{{ $total - $passed }}</p>
        <p class="text-xs text-slate-500 mt-1">Tidak Lulus</p>
    </div>
    <div class="ep-card ep-card-body text-center">
        <p class="text-3xl font-bold text-brand-600">{{ $avgScore }}</p>
        <p class="text-xs text-slate-500 mt-1">Rata-rata Skor</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- Distribusi Nilai --}}
    <div class="ep-card">
        <div class="ep-card-header">
            <h3 class="font-semibold text-slate-700 dark:text-slate-200">Distribusi Nilai</h3>
        </div>
        <div class="ep-card-body space-y-3">
            @php
                $bands = [
                    ['label' => 'Sangat Baik (≥90)', 'count' => $distribution['high'],   'color' => 'bg-emerald-500'],
                    ['label' => 'Baik (75–89)',       'count' => $distribution['medium'], 'color' => 'bg-brand-500'],
                    ['label' => 'Cukup (60–74)',      'count' => $distribution['low'],    'color' => 'bg-amber-500'],
                    ['label' => 'Kurang (<60)',        'count' => $distribution['fail'],   'color' => 'bg-red-500'],
                ];
            @endphp
            @foreach($bands as $band)
                @php $pct = $total > 0 ? round(($band['count'] / $total) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>{{ $band['label'] }}</span>
                        <span>{{ $band['count'] }} ({{ $pct }}%)</span>
                    </div>
                    <div class="h-2 bg-slate-100 dark:bg-navy-700 rounded-full overflow-hidden">
                        <div class="{{ $band['color'] }} h-2 rounded-full progress-fill" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
            @if($total === 0)
                <p class="text-sm text-slate-400 text-center py-4">Belum ada peserta ujian.</p>
            @endif
        </div>
    </div>

    {{-- Info Ujian --}}
    <div class="ep-card">
        <div class="ep-card-header">
            <h3 class="font-semibold text-slate-700 dark:text-slate-200">Detail Ujian</h3>
            @if($exam->isActive())
                <x-badge color="green" :dot="true">Aktif</x-badge>
            @else
                <x-badge color="gray" :dot="true">Tidak Aktif</x-badge>
            @endif
        </div>
        <div class="ep-card-body space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500">Jumlah soal</span>
                <span class="font-medium">{{ count($exam->questions) }} soal</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Durasi</span>
                <span class="font-medium">{{ $exam->duration_minutes }} menit</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nilai lulus</span>
                <span class="font-medium">{{ $exam->passing_score }}%</span>
            </div>
            @if($exam->start_date)
                <div class="flex justify-between">
                    <span class="text-slate-500">Mulai</span>
                    <span class="text-xs">{{ $exam->start_date->format('d M Y H:i') }}</span>
                </div>
            @endif
            @if($exam->end_date)
                <div class="flex justify-between">
                    <span class="text-slate-500">Selesai</span>
                    <span class="text-xs">{{ $exam->end_date->format('d M Y H:i') }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Analitik Per Soal --}}
    <div class="ep-card">
        <div class="ep-card-header">
            <h3 class="font-semibold text-slate-700 dark:text-slate-200">Analitik Per Soal</h3>
        </div>
        <div class="ep-card-body space-y-3">
            @forelse($exam->questions as $qi => $q)
                @php
                    $correctCount = 0;
                    foreach($submissions as $sub) {
                        if(isset($sub->answers[$qi]) && (int)$sub->answers[$qi] === (int)$q['correct']) $correctCount++;
                    }
                    $qPct = $total > 0 ? round(($correctCount / $total) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span class="truncate max-w-[150px]" title="{{ $q['question'] }}">
                            {{ $qi + 1 }}. {{ Str::limit($q['question'], 30) }}
                        </span>
                        <span>{{ $qPct }}% benar</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 dark:bg-navy-700 rounded-full overflow-hidden">
                        <div class="{{ $qPct >= 70 ? 'bg-emerald-500' : ($qPct >= 40 ? 'bg-amber-500' : 'bg-red-500') }}
                            h-1.5 rounded-full progress-fill" style="width: {{ $qPct }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400">Tidak ada soal.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Tabel Hasil --}}
<div class="ep-card overflow-hidden">
    <div class="ep-card-header">
        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Hasil Per Siswa</h3>
        <span class="text-sm text-slate-400">{{ $total }} peserta</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full ep-table">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Skor</th>
                    <th>Status</th>
                    <th>Benar / Total</th>
                    <th>Waktu Mulai</th>
                    <th>Dikumpulkan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                    @php
                        $correct = 0;
                        foreach($exam->questions as $qi => $q) {
                            if(isset($sub->answers[$qi]) && (int)$sub->answers[$qi] === (int)$q['correct']) $correct++;
                        }
                    @endphp
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($sub->user->name) }}&background=6366F1&color=fff&size=60"
                                    class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="font-medium text-sm text-slate-800 dark:text-slate-100">{{ $sub->user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $sub->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-lg font-bold {{ $sub->score >= $exam->passing_score ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $sub->score }}
                            </span>
                        </td>
                        <td>
                            @if($sub->is_passed)
                                <x-badge color="green" :dot="true">Lulus</x-badge>
                            @else
                                <x-badge color="red" :dot="true">Tidak Lulus</x-badge>
                            @endif
                        </td>
                        <td class="text-slate-600 dark:text-slate-300">
                            {{ $correct }} / {{ count($exam->questions) }}
                        </td>
                        <td class="text-slate-500 text-xs">
                            {{ $sub->started_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="text-slate-500 text-xs">
                            {{ $sub->submitted_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            Belum ada siswa yang mengerjakan ujian ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
