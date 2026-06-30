@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="font-display font-bold text-2xl text-slate-800 dark:text-white">Notifikasi</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                @if($unreadCount > 0)
                    <span class="text-brand-500 font-medium">{{ $unreadCount }} belum dibaca</span>
                @else
                    Semua notifikasi sudah dibaca
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            @if($unreadCount > 0)
                <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="ep-btn-outline ep-btn-sm">
                        ✓ Tandai Semua Dibaca
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.notifications.clear-read') }}" method="POST">
                @csrf
                <button type="submit" class="ep-btn-secondary ep-btn-sm"
                    onclick="return confirm('Hapus semua notifikasi yang sudah dibaca?')">
                    🗑 Hapus Sudah Dibaca
                </button>
            </form>
        </div>
    </div>

    {{-- ── Filter Bar ── --}}
    <form method="GET" class="flex flex-wrap gap-2 mb-5">
        <div class="flex gap-1 bg-slate-100 dark:bg-navy-800 p-1 rounded-xl">
            @foreach(['all' => 'Semua', 'unread' => 'Belum Dibaca', 'read' => 'Sudah Dibaca'] as $val => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'type' => $filterType]) }}"
                    class="px-3 py-1.5 text-sm rounded-lg transition-all
                    {{ $filterStatus === $val
                        ? 'bg-white dark:bg-navy-700 text-brand-600 dark:text-brand-400 font-semibold shadow-sm'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if($types->count() > 0)
            <select name="type"
                class="ep-input !py-1.5 !pl-3 text-sm"
                onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                @foreach($types as $type)
                    @php
                        $typeLabels = [
                            'assignment_graded'   => '📝 Tugas Dinilai',
                            'quiz_completed'      => '🎯 Kuis Selesai',
                            'exam_completed'      => '📋 Ujian Selesai',
                            'exam_available'      => '🗒️ Ujian Baru',
                            'new_lesson'          => '📚 Materi Baru',
                            'assignment_due_soon' => '⏰ Deadline Dekat',
                        ];
                    @endphp
                    <option value="{{ $type }}" {{ $filterType === $type ? 'selected' : '' }}>
                        {{ $typeLabels[$type] ?? ucwords(str_replace('_', ' ', $type)) }}
                    </option>
                @endforeach
            </select>
        @endif
    </form>

    {{-- ── Notification List ── --}}
    <div class="ep-card overflow-hidden">

        @forelse($notifications as $notif)
            @php
                $data     = $notif->data;
                $isUnread = is_null($notif->read_at);
                $icon     = $data['icon'] ?? '🔔';
                $title    = $data['title'] ?? 'Notifikasi';
                $body     = $data['body'] ?? '';
                $type     = $data['type'] ?? '';
                $typeLabel = [
                    'assignment_graded'   => 'Tugas Dinilai',
                    'quiz_completed'      => 'Kuis Selesai',
                    'exam_completed'      => 'Ujian Selesai',
                    'exam_available'      => 'Ujian Baru',
                    'new_lesson'          => 'Materi Baru',
                    'assignment_due_soon' => 'Deadline Dekat',
                ][$type] ?? ucwords(str_replace('_', ' ', $type));
                $badgeColor = [
                    'assignment_graded'   => 'blue',
                    'quiz_completed'      => 'green',
                    'exam_completed'      => 'violet',
                    'exam_available'      => 'brand',
                    'new_lesson'          => 'emerald',
                    'assignment_due_soon' => 'yellow',
                ][$type] ?? 'gray';
            @endphp

            <div class="flex items-start gap-4 px-5 py-4 border-b border-slate-100 dark:border-navy-700
                        transition-colors group
                        {{ $isUnread ? 'bg-brand-50/40 dark:bg-brand-500/5 hover:bg-brand-50/60 dark:hover:bg-brand-500/10'
                                     : 'hover:bg-slate-50 dark:hover:bg-navy-800/50' }}">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                            {{ $isUnread
                                ? 'bg-brand-100 dark:bg-brand-500/20'
                                : 'bg-slate-100 dark:bg-navy-700' }}">
                    {{ $icon }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-sm font-semibold {{ $isUnread ? 'text-slate-800 dark:text-white' : 'text-slate-600 dark:text-slate-300' }}">
                            {{ $title }}
                        </p>
                        <x-badge :color="$badgeColor" size="sm">{{ $typeLabel }}</x-badge>
                        @if($isUnread)
                            <span class="w-2 h-2 bg-brand-500 rounded-full flex-shrink-0"></span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                        {{ $body }}
                    </p>

                    {{-- Extra data untuk tipe tertentu --}}
                    @if(isset($data['score']))
                        <div class="mt-1.5 flex items-center gap-2">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-lg
                                {{ ($data['is_passed'] ?? false)
                                    ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                                    : 'bg-red-50 dark:bg-red-500/10 text-red-500 dark:text-red-400' }}">
                                Skor: {{ $data['score'] }}
                            </span>
                            @if(isset($data['feedback']) && $data['feedback'])
                                <span class="text-xs text-slate-400 italic truncate max-w-[300px]">
                                    "{{ $data['feedback'] }}"
                                </span>
                            @endif
                        </div>
                    @endif

                    <p class="text-xs text-slate-400 mt-1.5">{{ $notif->created_at->diffForHumans() }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                    @if($isUnread)
                        <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="ep-btn-icon ep-btn-secondary ep-btn-sm" title="Tandai dibaca">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.notifications.destroy', $notif->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="ep-btn-icon ep-btn-danger ep-btn-sm" title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center gap-4 py-20 text-slate-400">
                <svg class="w-16 h-16 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <div class="text-center">
                    <p class="font-semibold text-lg">Tidak ada notifikasi</p>
                    <p class="text-sm mt-1">
                        {{ $filterStatus !== 'all' || $filterType ? 'Coba ubah filter di atas.' : 'Notifikasi akan muncul di sini saat ada aktivitas baru.' }}
                    </p>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 dark:border-navy-700 flex items-center justify-between text-sm text-slate-500">
                <span>
                    {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }}
                    dari {{ $notifications->total() }} notifikasi
                </span>
                <div class="flex gap-1">
                    @if($notifications->onFirstPage())
                        <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 text-slate-400 cursor-not-allowed">←</span>
                    @else
                        <a href="{{ $notifications->previousPageUrl() }}"
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">←</a>
                    @endif
                    @if($notifications->hasMorePages())
                        <a href="{{ $notifications->nextPageUrl() }}"
                            class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 hover:bg-brand-50 dark:hover:bg-brand-500/10 text-slate-700 dark:text-slate-300">→</a>
                    @else
                        <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-700 text-slate-400 cursor-not-allowed">→</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

@endsection
