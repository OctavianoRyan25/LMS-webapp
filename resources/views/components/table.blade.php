{{--
|--------------------------------------------------------------------------
| components/table.blade.php  —  Komponen Tabel Reusable
|--------------------------------------------------------------------------
|
| PROPS:
|   $id         → string, id tabel (default: 'ep-table')
|   $columns    → array [ ['key' => 'name', 'label' => 'Nama', 'sortable' => true], ... ]
|   $searchable → bool, tampilkan search input (default: true)
|   $createRoute → string|null, route untuk tombol Tambah (default: null)
|   $createLabel → string, label tombol (default: 'Tambah')
|   $emptyText  → string, teks jika data kosong
|   $slot        → isi <tbody> baris dari pemanggil
|
| CARA PAKAI:
|
|   <x-table
|       :columns="[
|           ['key' => 'name',       'label' => 'Nama',       'sortable' => true],
|           ['key' => 'email',      'label' => 'Email',      'sortable' => true],
|           ['key' => 'status',     'label' => 'Status',     'sortable' => false],
|           ['key' => 'created_at', 'label' => 'Terdaftar',  'sortable' => true],
|           ['key' => 'actions',    'label' => 'Aksi',       'sortable' => false],
|       ]"
|       :searchable="true"
|       create-route="{{ route('admin.students.create') }}"
|       create-label="Tambah Siswa"
|   >
|       @foreach ($students as $student)
|           <tr>
|               <td>...</td>
|           </tr>
|       @endforeach
|   </x-table>
--}}

@props([
    'id' => 'ep-table',
    'columns' => [],
    'searchable' => true,
    'createRoute' => null,
    'createLabel' => 'Tambah',
    'emptyText' => 'Belum ada data.',
])

<div class="ep-card overflow-hidden" x-data="{
    search: '',
    sortKey: '',
    sortDir: 'asc',
    setSort(key) {
        if (this.sortKey === key) {
            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortKey = key;
            this.sortDir = 'asc';
        }
    }
}">

    {{-- Toolbar --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 px-5 py-4
                border-b border-slate-100 dark:border-navy-700">

        @if ($searchable)
            <div class="relative w-full sm:w-64">
                <input type="text" x-model="search" placeholder="Cari data..." class="ep-input !py-2 !pl-9 pr-4">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
            </div>
        @else
            <div></div>
        @endif

        <div class="flex items-center gap-2 flex-shrink-0">
            {{-- Slot untuk tombol tambahan --}}
            {{ $toolbar ?? '' }}

            @if ($createRoute)
                <a href="{{ $createRoute }}" class="ep-btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ $createLabel }}
                </a>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full ep-table" id="{{ $id }}">
            <thead>
                <tr>
                    @foreach ($columns as $col)
                        <th>
                            @if ($col['sortable'] ?? false)
                                <button @click="setSort('{{ $col['key'] }}')"
                                    class="flex items-center gap-1 group hover:text-slate-700 dark:hover:text-slate-200 transition-colors">
                                    {{ $col['label'] }}
                                    <span class="opacity-40 group-hover:opacity-100 transition-opacity">
                                        <svg x-show="sortKey !== '{{ $col['key'] }}'" class="w-3.5 h-3.5"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                        <svg x-show="sortKey === '{{ $col['key'] }}' && sortDir === 'asc'" x-cloak
                                            class="w-3.5 h-3.5 text-brand-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                        <svg x-show="sortKey === '{{ $col['key'] }}' && sortDir === 'desc'" x-cloak
                                            class="w-3.5 h-3.5 text-brand-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                            @else
                                {{ $col['label'] }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>

    {{-- Footer / Pagination --}}
    @isset($footer)
        <div class="px-5 py-3 border-t border-slate-100 dark:border-navy-700">
            {{ $footer }}
        </div>
    @endisset

</div>
