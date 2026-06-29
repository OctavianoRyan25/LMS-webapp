@extends('layouts.app')
@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')

    @php
        $isEdit    = isset($user);
        $formRoute = $isEdit
            ? route('admin.users.update', $user->id)
            : route('admin.users.store');
    @endphp

    <x-page-header :title="$isEdit ? 'Edit Pengguna' : 'Tambah Pengguna'"
        :subtitle="$isEdit ? $user->email : 'Buat akun pengguna baru'">
        <x-slot:actions>
            <a href="{{ route('admin.users.index') }}" class="ep-btn-secondary">← Kembali</a>
        </x-slot:actions>
    </x-page-header>

    <form action="{{ $formRoute }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Kolom Kiri --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Card: Informasi Akun --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Informasi Akun</h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        <x-form.input name="name" label="Nama Lengkap" :required="true"
                            placeholder="Contoh: John Doe"
                            :value="$user->name ?? null" />

                        <x-form.input name="email" label="Alamat Email" type="email" :required="true"
                            placeholder="user@email.com"
                            hint="Email digunakan untuk login."
                            :value="$user->email ?? null" />

                        <x-form.select name="role_id" label="Role" :required="true">
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    @selected(old('role_id', $user->role_id ?? null) == $role->id)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </x-form.select>

                    </div>
                </div>

                {{-- Card: Password --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">
                            {{ $isEdit ? 'Ganti Password' : 'Password' }}
                        </h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        @if($isEdit)
                            <div class="flex items-start gap-3 p-4 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-200 dark:border-amber-500/20">
                                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm text-amber-700 dark:text-amber-400">
                                    Kosongkan field ini jika tidak ingin mengubah password.
                                </p>
                            </div>
                        @endif

                        <div x-data="{ show: false }">
                            <label for="password" class="ep-label">
                                Password
                                @if(!$isEdit) <span class="text-red-500 ml-0.5">*</span> @endif
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'"
                                    id="password" name="password"
                                    class="ep-input pr-12 @error('password') ep-input-error @enderror"
                                    placeholder="{{ $isEdit ? 'Biarkan kosong jika tidak diubah' : 'Min. 8 karakter' }}"
                                    @if(!$isEdit) required @endif>
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="ep-error-msg">{{ $message }}</p>
                            @enderror
                            <p class="ep-hint">Minimal 8 karakter, kombinasi huruf dan angka.</p>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-5">

                {{-- Avatar --}}
                <div class="ep-card">
                    <div class="ep-card-body flex flex-col items-center gap-3 text-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(old('name', $user->name ?? 'User')) }}&background=6366F1&color=fff&size=120"
                            class="w-24 h-24 rounded-full shadow-lg" alt="Avatar">
                        <div>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-100">
                                {{ $isEdit ? $user->name : 'Pengguna Baru' }}
                            </p>
                            @if($isEdit)
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Bergabung {{ $user->created_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="ep-card">
                    <div class="ep-card-body space-y-3">

                        <button type="submit" class="ep-btn-primary w-full" :disabled="loading">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            <span x-text="loading ? 'Menyimpan...' : '{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}'"></span>
                        </button>

                        <a href="{{ route('admin.users.index') }}" class="ep-btn-secondary w-full text-center block">
                            Batalkan
                        </a>

                        @if($isEdit && $user->id !== auth()->id())
                            <hr class="border-slate-100 dark:border-navy-700">
                            <button type="button" class="ep-btn-danger w-full"
                                onclick="EP.deleteForm(this, '{{ $user->name }} akan dihapus permanen.')"
                                data-form-id="delete-user-form">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Pengguna
                            </button>
                        @endif

                    </div>
                </div>

                {{-- Info role --}}
                @if($isEdit)
                    <div class="ep-card">
                        <div class="ep-card-header">
                            <h3 class="font-semibold text-slate-700 dark:text-slate-200 text-sm">Info Akun</h3>
                        </div>
                        <div class="ep-card-body space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">ID</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Status</span>
                                @if($user->email_verified_at)
                                    <x-badge color="green" size="sm">Terverifikasi</x-badge>
                                @else
                                    <x-badge color="yellow" size="sm">Belum Verifikasi</x-badge>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Role</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $user->role->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Dibuat</span>
                                <span class="text-slate-700 dark:text-slate-300">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </form>

    {{-- Delete form di luar form utama --}}
    @if($isEdit && $user->id !== auth()->id())
        <form id="delete-user-form" action="{{ route('admin.users.destroy', $user) }}"
              method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
@endpush
