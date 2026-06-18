{{-- layouts/partials/topbar.blade.php --}}

{{--
| Variabel opsional:
|   $pageTitle    → judul halaman (default: dari @section('title'))
|   $breadcrumbs → array [ ['label' => 'Kursus', 'url' => '/...'], ... ]
--}}

<header class="card-bg border-b border-slate-200 dark:border-navy-700 flex items-center justify-between px-5 py-3 flex-shrink-0 z-10">

    <div class="flex items-center gap-3">

        {{-- Mobile menu button --}}
        <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-800 transition-all"
                aria-label="Toggle menu">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Page title & breadcrumb --}}
        <div>
            <h1 class="font-display font-bold text-base lg:text-lg text-slate-800 dark:text-white leading-tight">
                @yield('title', 'Dashboard')
            </h1>
            @if(isset($breadcrumbs) && count($breadcrumbs))
                <nav class="flex items-center gap-1 text-xs text-slate-400 mt-0.5 hidden sm:flex">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-500 transition-colors">Home</a>
                    @foreach($breadcrumbs as $crumb)
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        @if(!$loop->last)
                            <a href="{{ $crumb['url'] }}" class="hover:text-brand-500 transition-colors">{{ $crumb['label'] }}</a>
                        @else
                            <span class="text-slate-600 dark:text-slate-300 font-medium">{{ $crumb['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-1.5">

        {{-- Search --}}
        <div class="relative hidden md:block">
            <input type="text"
                   placeholder="Cari kursus, siswa..."
                   class="w-52 pl-9 pr-4 py-2 text-sm bg-slate-100 dark:bg-navy-800 border border-slate-200 dark:border-navy-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500/40 focus:border-brand-500 dark:text-slate-200 placeholder-slate-400 transition-all">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
        </div>

        {{-- Dark mode toggle --}}
        <button @click="darkMode = !darkMode"
                class="p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-navy-800 transition-all"
                :title="darkMode ? 'Mode Terang' : 'Mode Gelap'">
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>

        {{-- Notifications --}}
        <div class="relative" x-data="notifDropdown()">
            <button @click="open = !open"
                    class="relative p-2 rounded-xl text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-navy-800 transition-all"
                    aria-label="Notifikasi">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @php $unreadCount = 3; /* ganti dengan: auth()->user()->unreadNotifications->count() */ @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full pulse-dot"></span>
                @endif
            </button>

            <div x-show="open" x-cloak
                 @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-1"
                 class="absolute right-0 mt-2 w-80 card-bg border border-slate-200 dark:border-navy-700 rounded-2xl shadow-xl overflow-hidden z-50">

                <div class="px-4 py-3 border-b border-slate-100 dark:border-navy-700 flex justify-between items-center">
                    <p class="font-semibold text-sm">Notifikasi</p>
                    <span class="text-xs bg-brand-50 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 px-2 py-0.5 rounded-full font-semibold">
                        {{ $unreadCount }} baru
                    </span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-navy-700 max-h-72 overflow-y-auto">
                    {{-- Ganti dengan: @foreach(auth()->user()->notifications->take(5) as $notif) --}}
                    @php
                        $demoNotifs = [
                            ['icon' => '👥', 'title' => '12 siswa baru mendaftar hari ini', 'time' => '5 menit lalu', 'unread' => true],
                            ['icon' => '📚', 'title' => 'Kursus "React Native" menunggu review', 'time' => '1 jam lalu', 'unread' => true],
                            ['icon' => '🏆', 'title' => '89 sertifikat diterbitkan bulan ini', 'time' => '3 jam lalu', 'unread' => false],
                        ];
                    @endphp
                    @foreach($demoNotifs as $notif)
                        <div class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors cursor-pointer">
                            <span class="text-xl flex-shrink-0 mt-0.5">{{ $notif['icon'] }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200 leading-snug">{{ $notif['title'] }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $notif['time'] }}</p>
                            </div>
                            @if($notif['unread'])
                                <span class="w-2 h-2 bg-brand-500 rounded-full mt-1.5 flex-shrink-0"></span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="px-4 py-2.5 text-center border-t border-slate-100 dark:border-navy-700">
                    <a href="{{ route('admin.notifications') }}"
                       class="text-sm text-brand-500 hover:text-brand-600 font-medium transition-colors">
                        Lihat semua notifikasi →
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>

@push('scripts')
<script>
function notifDropdown() {
    return { open: false }
}
</script>
@endpush