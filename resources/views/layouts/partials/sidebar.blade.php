{{-- layouts/partials/sidebar.blade.php --}}

{{--
| Variabel yang dibutuhkan dari controller/view:
|   $activeNav  → string key menu yang aktif (default: 'dashboard')
|
| Cara set di controller:
|   return view('admin.dashboard', ['activeNav' => 'dashboard']);
--}}

@php
    $activeNav = $activeNav ?? (request()->segment(2) ?? 'dashboard');

    $navItems = [
        [
            'key' => 'dashboard',
            'label' => 'Dashboard',
            'href' => route('admin.dashboard'),
            'badge' => null,
            'icon' =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
        ],
        [
            'key' => 'courses',
            'label' => 'Kursus',
            'href' => route('admin.courses.index'),
            'badge' => null,
            'icon' =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
        ],
        [
            'key' => 'users',
            'label' => 'Pengguna',
            'href' => route('admin.users.index'),
            'badge' => null,
            'icon' =>
                '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>',
        ],
    ];

    // Nav penilaian — tampil di bagian bawah sebelum settings
    $assessmentItems = [
        [
            'key' => 'quizzes',
            'label' => 'Kuis',
            'href' => route('admin.quizzes.index'),
            'icon' =>
                '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open-text-icon lucide-book-open-text"><path d="M12 7v14"/><path d="M16 12h2"/><path d="M16 8h2"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/><path d="M6 12h2"/><path d="M6 8h2"/></svg>',
        ],
        [
            'key' => 'assignments',
            'label' => 'Tugas',
            'href' => route('admin.assignments.index'),
            'icon' =>
                '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-notebook-pen-icon lucide-notebook-pen"><path d="M13.4 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7.4"/><path d="M2 6h4"/><path d="M2 10h4"/><path d="M2 14h4"/><path d="M2 18h4"/><path d="M21.378 5.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/></svg>',
        ],
        [
            'key' => 'exams',
            'label' => 'Ujian',
            'href' => route('admin.exams.index'),
            'icon' =>
                '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-check-icon lucide-book-check"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/><path d="m9 9.5 2 2 4-4"/></svg>',
        ],
    ];

    $settingItems = [
        [
            'key'   => 'notifications',
            'label' => 'Notifikasi',
            'href'  => route('admin.notifications'),
            'icon'  =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>',
        ],
        [
            'key'   => 'settings',
            'label' => 'Pengaturan',
            'href'  => route('admin.settings'),
            'icon'  =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        ],
    ];
@endphp

<aside class="sidebar sidebar-bg z-30 flex-shrink-0 flex flex-col w-64"
    :class="[
        isMobile ?
        (sidebarOpen ? 'fixed inset-y-0 left-0 translate-x-0' : 'fixed inset-y-0 left-0 -translate-x-full') :
        'relative'
    ]">

    {{-- Logo --}}
    <div class="flex items-center px-5 py-5 border-b border-slate-200 dark:border-white/5 justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <span class="font-display font-bold text-lg text-slate-800 dark:text-white tracking-tight">
                EduPanel
            </span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2 mt-1">
            Menu Utama
        </p>

        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all group"
                @class([
                    // Active: brand color on both light & dark
                    'nav-item-active text-brand-600 dark:text-brand-400' =>
                        $activeNav === $item['key'],
                    // Inactive: slate-500 on light, slate-400 on dark
                    'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-800 dark:hover:text-slate-200' =>
                        $activeNav !== $item['key'],
                ]) title="{{ $item['label'] }}">
                {!! $item['icon'] !!}
                <span class="text-sm font-medium flex-1">
                    {{ $item['label'] }}
                </span>
                @if ($item['badge'])
                    <span class="ml-auto text-xs bg-brand-500 text-white px-1.5 py-0.5 rounded-full font-semibold">
                        {{ $item['badge'] }}
                    </span>
                @endif
            </a>
        @endforeach

        @foreach ($assessmentItems as $item)
            <a href="{{ $item['href'] }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                @class([
                    'nav-item-active text-brand-600 dark:text-brand-400' =>
                        $activeNav === $item['key'],
                    'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-800 dark:hover:text-slate-200' =>
                        $activeNav !== $item['key'],
                ]) title="{{ $item['label'] }}">
                {!! $item['icon'] !!}
                <span class="text-sm font-medium">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach

        {{-- Jarak Pembatas Diperlebar (mt-8) --}}
        <div class="mt-8 mb-3 border-t border-slate-200 dark:border-white/5"></div>

        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">
            Pengaturan
        </p>

        @php $sidebarUnread = auth()->user()->unreadNotifications()->count(); @endphp
        @foreach ($settingItems as $item)
            <a href="{{ $item['href'] }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                @class([
                    'nav-item-active text-brand-600 dark:text-brand-400' =>
                        $activeNav === $item['key'],
                    'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-800 dark:hover:text-slate-200' =>
                        $activeNav !== $item['key'],
                ]) title="{{ $item['label'] }}">
                {!! $item['icon'] !!}
                <span class="text-sm font-medium flex-1">
                    {{ $item['label'] }}
                </span>
                @if($item['key'] === 'notifications' && $sidebarUnread > 0)
                    <span class="ml-auto text-xs bg-red-500 text-white px-1.5 py-0.5 rounded-full font-semibold min-w-[1.25rem] text-center">
                        {{ $sidebarUnread > 99 ? '99+' : $sidebarUnread }}
                    </span>
                @endif
            </a>
        @endforeach

    </nav>

    {{-- User profile --}}
    <div class="p-3 border-t border-slate-200 dark:border-white/5">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'Admin') . '&background=6366F1&color=fff&size=80' }}"
                class="w-9 h-9 rounded-full avatar-ring flex-shrink-0" alt="{{ auth()->user()->name ?? 'Admin' }}">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                    {{ auth()->user()->name ?? 'Admin' }}
                </p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@edupanel.id' }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit"
                    class="text-slate-400 hover:text-red-500 dark:hover:text-slate-300 transition-colors p-1"
                    title="Logout">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>
