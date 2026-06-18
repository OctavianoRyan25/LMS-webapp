@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')

    <x-page-header title="Notifikasi" subtitle="Kelola semua notifikasi sistem" />

    <div class="ep-card mt-2">
        <div class="ep-card-body flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-navy-800 flex items-center justify-center text-3xl mb-4">
                🔔
            </div>
            <h3 class="font-semibold text-slate-700 dark:text-slate-200 text-lg mb-2">Belum Ada Notifikasi</h3>
            <p class="text-sm text-slate-400 max-w-sm">Semua notifikasi sistem akan muncul di sini.</p>
        </div>
    </div>

@endsection
