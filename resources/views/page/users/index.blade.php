@extends('layouts.app')
@section('title', 'Manajemen Pengguna')

@section('content')

    <x-page-header title="Manajemen Pengguna" subtitle="Kelola semua pengguna sistem">
        <x-slot:actions>
            <a href="{{ route('admin.users.create') }}" class="ep-btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pengguna
            </a>
        </x-slot:actions>
    </x-page-header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <span class="text-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-brand-500" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-icon lucide-user">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </span>
                <div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($stats['total']) }}</p>
                    <p class="text-xs text-slate-500">Total Pengguna</p>
                </div>
            </div>
        </div>
        <div class="ep-card stat-card">
            <div class="ep-card-body flex items-center gap-3">
                <span class="text-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-500" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-round-check-icon lucide-user-round-check">
                        <path d="M2 21a8 8 0 0 1 13.292-6" />
                        <circle cx="10" cy="8" r="5" />
                        <path d="m16 19 2 2 4-4" />
                    </svg>
                </span>
                <div>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($stats['active']) }}
                    </p>
                    <p class="text-xs text-slate-500">Terverifikasi</p>
                </div>
            </div>
        </div>
        <div class="ep-card ep-card-body flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">
                    {{ number_format($stats['total'] - $stats['active']) }}
                </p>
                <p class="text-xs text-slate-500">Belum Verifikasi</p>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    {{-- <form method="GET" class="flex flex-wrap gap-3 mb-4">
        <div class="relative flex-1 min-w-48">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                class="ep-input !py-2 pl-9">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
            </svg>
        </div>
        <select name="role" class="ep-select !py-2 w-40">
            <option value="">Semua Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(request('role') == $role->id)>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="ep-btn-secondary">Filter</button>
        @if (request()->hasAny(['search', 'role']))
            <a href="{{ route('admin.users.index') }}" class="ep-btn-outline">Reset</a>
        @endif
    </form> --}}

    {{-- Table --}}
    <x-table id="userTable" :columns="[
        ['key' => 'name', 'label' => 'Pengguna', 'sortable' => true],
        ['key' => 'email', 'label' => 'Email', 'sortable' => true],
        ['key' => 'role', 'label' => 'Role', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'sortable' => true],
        ['key' => 'created_at', 'label' => 'Bergabung', 'sortable' => true],
    ]" :create-route="route('admin.users.create')" create-label="Tambah Pengguna" searchable="true">
        <div class="ep-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full ep-table">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Bergabung</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ep-td">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366F1&color=fff&size=80"
                                            class="w-9 h-9 rounded-full flex-shrink-0" alt="{{ $user->name }}">
                                        <div>
                                            <p class="font-medium text-slate-800 dark:text-slate-100">
                                                {{ $user->name }}
                                                @if ($user->id === auth()->id())
                                                    <span class="text-xs text-brand-500 ml-1">(Anda)</span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="ep-td">
                                    @php
                                        $roleColors = [
                                            'admin' => 'purple',
                                            'instructor' => 'blue',
                                            'student' => 'gray',
                                        ];
                                        $rc = $roleColors[strtolower($user->role->name ?? '')] ?? 'gray';
                                    @endphp
                                    <x-badge :color="$rc">{{ $user->role->name ?? '-' }}</x-badge>
                                </td>
                                <td class="ep-td">
                                    @if ($user->email_verified_at)
                                        <x-badge color="green" :dot="true">Aktif</x-badge>
                                    @else
                                        <x-badge color="yellow" :dot="true">Belum Verifikasi</x-badge>
                                    @endif
                                </td>
                                <td class="ep-td text-slate-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="ep-td">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="ep-btn-outline ep-btn-sm">Edit</a>
                                        @if ($user->id !== auth()->id())
                                            <button type="button"
                                                class="ep-btn-sm ep-btn
                                            border border-red-200 dark:border-red-500/30
                                            text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10"
                                                onclick="EP.deleteForm(this, '{{ $user->name }} akan dihapus permanen.')"
                                                data-form-id="delete-user-{{ $user->id }}">
                                                Hapus
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="ep-td text-center py-12 text-slate-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Tidak ada pengguna ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="px-5 py-3 border-t border-slate-100 dark:border-navy-700">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </x-table>

    {{-- Delete forms (di luar tabel) --}}
    @foreach ($users as $user)
        @if ($user->id !== auth()->id())
            <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST"
                class="hidden">
                @csrf @method('DELETE')
            </form>
        @endif
    @endforeach

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
@endpush
