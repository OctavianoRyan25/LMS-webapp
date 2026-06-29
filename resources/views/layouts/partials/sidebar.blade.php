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
            'key'   => 'dashboard',
            'label' => 'Dashboard',
            'href'  => route('admin.dashboard'),
            'badge' => null,
            'icon'  =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
        ],
        [
            'key'   => 'courses',
            'label' => 'Kursus',
            'href'  => route('admin.courses.index'),
            'badge' => null,
            'icon'  =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
        ],
        [
            'key'   => 'users',
            'label' => 'Pengguna',
            'href'  => route('admin.users.index'),
            'badge' => null,
            'icon'  =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        ],
    ];

    // Nav penilaian — tampil di bagian bawah sebelum settings
    $assessmentItems = [
        [
            'key'   => 'quizzes',
            'label' => 'Kuis',
            'href'  => route('admin.courses.index'),   // menuju kursus → lesson → kuis
            'icon'  =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        ],
        [
            'key'   => 'assignments',
            'label' => 'Tugas',
            'href'  => route('admin.courses.index'),
            'icon'  =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>',
        ],
        [
            'key'   => 'exams',
            'label' => 'Ujian',
            'href'  => route('admin.courses.index'),
            'icon'  =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
        ],
    ];

    $settingItems = [
        [
            'key' => 'settings',
            'label' => 'Pengaturan',
            'href' => route('admin.settings'),
            'icon' =>
                '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        ],
    ];
@endphp

<aside class="sidebar sidebar-bg z-30 flex-shrink-0 flex flex-col"
    :class="[
        sidebarCollapsed ? 'w-20' : 'w-64',
        isMobile ?
        (sidebarOpen ? 'fixed inset-y-0 left-0 translate-x-0' : 'fixed inset-y-0 left-0 -translate-x-full') :
        'relative'
    ]">

    {{-- Logo --}}
    <div class="flex items-center px-5 py-5 border-b border-slate-200 dark:border-white/5"
        :class="sidebarCollapsed ? 'justify-center' : 'justify-between'">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <span x-show="!sidebarCollapsed" x-cloak
                class="font-display font-bold text-lg text-slate-800 dark:text-white tracking-tight">
                EduPanel
            </span>
        </div>
        <button x-show="!sidebarCollapsed" x-cloak @click="sidebarCollapsed = true"
            class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg
                   hover:bg-slate-100 dark:hover:bg-white/5 transition-colors hidden lg:block"
            title="Tutup sidebar">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <button x-show="sidebarCollapsed" x-cloak @click="sidebarCollapsed = false"
        class="mx-auto mt-3 text-slate-400 hover:text-slate-600 dark:hover:text-white p-2 rounded-lg
               hover:bg-slate-100 dark:hover:bg-white/5 transition-colors hidden lg:flex"
        title="Buka sidebar">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
        </svg>
    </button>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

        <p x-show="!sidebarCollapsed" x-cloak
            class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2 mt-1">
            Menu Utama
        </p>

        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all group"
                :class="sidebarCollapsed ? 'justify-center' : ''" @class([
                    // Active: brand color on both light & dark
                    'nav-item-active text-brand-600 dark:text-brand-400' =>
                        $activeNav === $item['key'],
                    // Inactive: slate-500 on light, slate-400 on dark
                    'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-800 dark:hover:text-slate-200' =>
                        $activeNav !== $item['key'],
                ])
                title="{{ $item['label'] }}">
                {!! $item['icon'] !!}
                <span x-show="!sidebarCollapsed" x-cloak class="text-sm font-medium flex-1">
                    {{ $item['label'] }}
                </span>
                @if ($item['badge'])
                    <span x-show="!sidebarCollapsed" x-cloak
                        class="ml-auto text-xs bg-brand-500 text-white px-1.5 py-0.5 rounded-full font-semibold">
                        {{ $item['badge'] }}
                    </span>
                @endif
            </a>
        @endforeach

        <div class="my-4 border-t border-slate-200 dark:border-white/5"></div>

        <p x-show="!sidebarCollapsed" x-cloak
            class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">
            Penilaian
        </p>

        @foreach ($assessmentItems as $item)
            <a href="{{ $item['href'] }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                :class="sidebarCollapsed ? 'justify-center' : ''" @class([
                    'nav-item-active text-brand-600 dark:text-brand-400' => $activeNav === $item['key'],
                    'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-800 dark:hover:text-slate-200' =>
                        $activeNav !== $item['key'],
                ])
                title="{{ $item['label'] }}">
                {!! $item['icon'] !!}
                <span x-show="!sidebarCollapsed" x-cloak class="text-sm font-medium">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach

        <div class="my-4 border-t border-slate-200 dark:border-white/5"></div>

        <p x-show="!sidebarCollapsed" x-cloak
            class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-2">
            Pengaturan
        </p>

        @foreach ($settingItems as $item)
            <a href="{{ $item['href'] }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl cursor-pointer transition-all"
                :class="sidebarCollapsed ? 'justify-center' : ''" @class([
                    'nav-item-active text-brand-600 dark:text-brand-400' =>
                        $activeNav === $item['key'],
                    'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-800 dark:hover:text-slate-200' =>
                        $activeNav !== $item['key'],
                ])
                title="{{ $item['label'] }}">
                {!! $item['icon'] !!}
                <span x-show="!sidebarCollapsed" x-cloak class="text-sm font-medium">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach

    </nav>

    {{-- User profile --}}
    <div class="p-3 border-t border-slate-200 dark:border-white/5">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 transition-colors"
            :class="sidebarCollapsed ? 'justify-center' : ''">
            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'Admin') . '&background=6366F1&color=fff&size=80' }}"
                class="w-9 h-9 rounded-full avatar-ring flex-shrink-0" alt="{{ auth()->user()->name ?? 'Admin' }}">
            <div x-show="!sidebarCollapsed" x-cloak class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                    {{ auth()->user()->name ?? 'Admin' }}
                </p>
                <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@edupanel.id' }}</p>
            </div>
            <form x-show="!sidebarCollapsed" x-cloak action="{{ route('logout') }}" method="POST"
                class="flex-shrink-0">
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
