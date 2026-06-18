{{--
|--------------------------------------------------------------------------
| pages/courses/create.blade.php  —  Contoh Halaman: Form Tambah/Edit Kursus
|--------------------------------------------------------------------------
| Bisa dipakai untuk Create dan Edit (kondisional berdasarkan $course)
|
| Controller create:
|   return view('admin.courses.create', ['activeNav' => 'courses']);
|
| Controller edit:
|   return view('admin.courses.create', [
|       'activeNav' => 'courses',
|       'course'    => $course,
|   ]);
--}}

@extends('layouts.app')

@section('title', isset($course) ? 'Edit Kursus' : 'Tambah Kursus')

@section('content')

    @php
        $isEdit = isset($course);
        $formTitle = $isEdit ? 'Edit Kursus' : 'Tambah Kursus Baru';
        $formRoute = $isEdit ? route('admin.courses.update', $course->id) : route('admin.courses.store');
    @endphp

    {{-- ── Page Header ── --}}
    <x-page-header :title="$formTitle" subtitle="Isi detail kursus di bawah ini">
        <x-slot:actions>
            <a href="{{ route('admin.courses.index') }}" class="ep-btn-secondary">
                ← Kembali
            </a>
        </x-slot:actions>
    </x-page-header>

    {{-- ── Form ── --}}
    <form action="{{ $formRoute }}" method="POST" enctype="multipart/form-data" x-data="courseForm()"
        @submit="handleSubmit">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ── Kolom Kiri (2/3) ── --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Card: Informasi Dasar --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Informasi Dasar</h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        <x-form.input name="title" label="Nama Kursus" placeholder="Contoh: Python untuk Pemula"
                            :value="$course->title ?? null" :required="true" hint="Gunakan nama yang jelas dan deskriptif." />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-form.select name="category_id" label="Kategori" :required="true">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach (['Pemrograman', 'Desain', 'Marketing', 'Produktivitas', 'Seni', 'Bisnis'] as $cat)
                                    <option value="{{ $cat }}" @selected(old('category_id', $course->category ?? '') == $cat)>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </x-form.select>

                            <x-form.select name="level" label="Level" :required="true">
                                <option value="">-- Pilih Level --</option>
                                <option value="beginner" @selected(old('level', $course->level ?? '') == 'beginner')>Pemula</option>
                                <option value="intermediate" @selected(old('level', $course->level ?? '') == 'intermediate')>Menengah</option>
                                <option value="advanced" @selected(old('level', $course->level ?? '') == 'advanced')>Lanjutan</option>
                            </x-form.select>
                        </div>

                        <x-form.textarea name="description" label="Deskripsi Kursus"
                            placeholder="Jelaskan isi, manfaat, dan target peserta kursus ini..." :value="$course->description ?? null"
                            :rows="5" hint="Minimal 100 karakter." />

                    </div>
                </div>

                {{-- Card: Pengaturan Kursus --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Pengaturan</h3>
                    </div>
                    <div class="ep-card-body space-y-4">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-form.input name="price" label="Harga (Rp)" type="number" placeholder="0" :value="$course->price ?? 0"
                                prefix="Rp" hint="Isi 0 untuk kursus gratis." />

                            <x-form.input name="duration_hours" label="Durasi (Jam)" type="number" placeholder="10"
                                :value="$course->duration_hours ?? null" suffix="jam" />
                        </div>

                        <x-form.select name="status" label="Status Publikasi" :required="true">
                            <option value="draft" @selected(old('status', $course->status ?? 'draft') == 'draft')>Draf — Belum Dipublikasi</option>
                            <option value="published" @selected(old('status', $course->status ?? '') == 'published')>Dipublikasi — Publik</option>
                            <option value="archived" @selected(old('status', $course->status ?? '') == 'archived')>Diarsipkan</option>
                        </x-form.select>

                        {{-- Toggle --}}
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-navy-800/60 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-200">Sertifikat Otomatis</p>
                                <p class="text-xs text-slate-400 mt-0.5">Terbitkan sertifikat saat kursus selesai</p>
                            </div>
                            <div x-data="{ on: {{ $course->has_certificate ?? true ? 'true' : 'false' }} }">
                                <input type="hidden" name="has_certificate" :value="on ? '1' : '0'">
                                <button type="button" @click="on = !on"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500/50"
                                    :class="on ? 'bg-brand-500' : 'bg-slate-300 dark:bg-slate-600'">
                                    <span
                                        class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"
                                        :class="on ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── Kolom Kanan (1/3) ── --}}
            <div class="space-y-5">

                {{-- Card: Thumbnail --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Thumbnail</h3>
                    </div>
                    <div class="ep-card-body">
                        <div class="border-2 border-dashed border-slate-200 dark:border-navy-600 rounded-xl p-6 text-center"
                            x-data="{ preview: '{{ $course->thumbnail_url ?? '' }}' }" @dragover.prevent
                            @drop.prevent="preview = URL.createObjectURL($event.dataTransfer.files[0])">

                            <template x-if="preview">
                                <img :src="preview" class="w-full h-36 object-cover rounded-lg mb-3">
                            </template>
                            <template x-if="!preview">
                                <div class="py-4">
                                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-slate-400">Drag & drop atau</p>
                                </div>
                            </template>

                            <label class="ep-btn-secondary ep-btn-sm cursor-pointer mt-1 inline-flex">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Pilih Gambar
                                <input type="file" name="thumbnail" accept="image/*" class="hidden"
                                    @change="preview = URL.createObjectURL($event.target.files[0])">
                            </label>
                            <p class="text-xs text-slate-400 mt-2">PNG, JPG, WebP maks. 2MB</p>
                        </div>
                    </div>
                </div>

                {{-- Card: Instruktur --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Instruktur</h3>
                    </div>
                    <div class="ep-card-body">
                        <x-form.select name="instructor_id" label="Pilih Instruktur" :required="true">
                            <option value="">-- Pilih Instruktur --</option>
                            {{-- @foreach ($instructors as $ins) --}}
                            @foreach (['Budi Santoso', 'Siti Rahayu', 'Andi Wijaya', 'Rina Kusuma'] as $ins)
                                <option value="{{ $ins }}" @selected(old('instructor_id', $course->instructor ?? '') == $ins)>
                                    {{ $ins }}
                                </option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="ep-card">
                    <div class="ep-card-body space-y-3">

                        {{-- Submit --}}
                        <button type="submit" class="ep-btn-primary w-full" :disabled="loading">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                            <span
                                x-text="loading ? 'Menyimpan...' : '{{ $isEdit ? 'Simpan Perubahan' : 'Buat Kursus' }}'"></span>
                        </button>

                        {{-- Batal --}}
                        <button type="button" class="ep-btn-secondary w-full"
                            @click="EP.confirm.custom({
                                    title: 'Batalkan perubahan?',
                                    text: 'Data yang belum disimpan akan hilang.',
                                    confirmText: 'Ya, batalkan',
                                    icon: 'warning',
                                    onConfirm: () => window.location = '{{ route('admin.courses.index') }}'
                                })">
                            Batalkan
                        </button>

                        @if ($isEdit)
                            <hr class="border-slate-100 dark:border-navy-700">
                            <button type="button" class="ep-btn-danger w-full"
                                onclick="EP.deleteForm(this, 'Kursus ini akan dihapus permanen beserta semua datanya.')"
                                data-form-id="delete-course-form">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Kursus Ini
                            </button>
                        @endif

                    </div>
                </div>

            </div>
        </div>

    </form>

    {{-- !! Form hapus harus di LUAR form utama !!                           --}}
    {{-- Nested form = HTML tidak valid → browser menyertakan _method=DELETE --}}
    {{-- ke form utama sehingga "Simpan Perubahan" malah memanggil destroy() --}}
    @if ($isEdit)
        <form id="delete-course-form" action="{{ route('admin.courses.destroy', $course->id) }}"
            method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endif

@endsection

@push('scripts')
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script>
        function courseForm() {
            return {
                loading: false,
                handleSubmit() {
                    this.loading = true;
                    // Form akan submit secara normal ke server
                }
            }
        }
    </script>
@endpush
