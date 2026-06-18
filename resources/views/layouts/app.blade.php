{{--
|--------------------------------------------------------------------------
| layouts/app.blade.php  —  Layout Utama EduPanel
|--------------------------------------------------------------------------
| Cara pakai:
|   @extends('layouts.app')
|   @section('title', 'Judul Halaman')
|   @section('content') ... @endsection
--}}

<!DOCTYPE html>
<html lang="id"
      x-data="appLayout()"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — EduPanel LMS</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=DM+Sans:wght@500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS (ganti dengan Vite jika sudah setup) --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Global Tailwind Config --}}
    {{-- @include('layouts.partials.tailwind-config') --}}

    {{-- Global CSS --}}
    @vite(['resources/css/app.css'])
    {{-- Atau jika belum pakai Vite: --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}

    {{-- Per-page styles --}}
    @stack('styles')
</head>

<body class="font-sans bg-slate-100 dark:bg-navy-900 text-slate-800 dark:text-slate-100 min-h-screen antialiased">

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen && isMobile"
         x-cloak
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-20 lg:hidden">
    </div>

    <div class="flex h-screen overflow-hidden">

        {{-- ===== SIDEBAR ===== --}}
        @include('layouts.partials.sidebar')

        {{-- ===== MAIN WRAPPER ===== --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- ===== TOPBAR ===== --}}
            @include('layouts.partials.topbar')

            {{-- ===== PAGE CONTENT ===== --}}
            <main class="flex-1 overflow-y-auto p-5 lg:p-6">
                @yield('content')
            </main>

        </div>
    </div>

    {{-- SweetAlert Flash Messages --}}
    @include('layouts.partials.flash-alerts')

    {{-- Alpine App Layout Script --}}
    <script src="{{ asset('js/app-layout.js') }}"></script>
    {{-- Atau inline: --}}
    @include('layouts.partials.app-script')

    {{-- Per-page scripts --}}
    @stack('scripts')

</body>
</html>