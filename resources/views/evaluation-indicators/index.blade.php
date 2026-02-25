@extends('layouts.app')

@section('content')
<div x-data="{
    showModal: false,
    editMode: false,
    formAction: '{{ route('evaluation-indicators.store') }}',
    formMethod: 'POST',
    indicatorName: '',

    openCreate() {
        this.editMode = false;
        this.indicatorName = '';
        this.formAction = '{{ route('evaluation-indicators.store') }}';
        this.formMethod = 'POST';
        this.showModal = true;
    },
    openEdit(id, name) {
        this.editMode = true;
        this.indicatorName = name;
        this.formAction = `{{ url('evaluation-indicators') }}/${id}`;
        this.formMethod = 'PUT';
        this.showModal = true;
    },
    closeModal() {
        this.showModal = false;
    }
}" class="space-y-4 sm:space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Indikator Penilaian</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola indikator penilaian PKL</p>
        </div>
        <button @click="openCreate()" type="button"
                class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-xl bg-school-blue hover:bg-school-blue/90 text-white font-bold text-sm transition-colors shadow-lg shadow-school-blue/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Indikator
        </button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center gap-2.5 p-3 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 dark:bg-green-500/10 dark:text-green-400 dark:border-green-800/40" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
            <p><span class="font-bold">Berhasil!</span> {{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center gap-2.5 p-3 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800/40" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/></svg>
            <p><span class="font-bold">Gagal!</span> {{ session('error') }}</p>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-3 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800/40" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Table --}}
    @if($indicators->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 sm:py-20 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-3xl">
            <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 mb-4">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Indikator</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center">Klik tombol "Tambah Indikator" untuk menambahkan indikator penilaian baru.</p>
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface overflow-hidden">
            {{-- Desktop Table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-amoled-surface dark:text-gray-400 border-b border-gray-200 dark:border-amoled-border">
                        <tr>
                            <th class="px-5 py-3 font-semibold w-16">No</th>
                            <th class="px-5 py-3 font-semibold">Nama Indikator</th>
                            <th class="px-5 py-3 font-semibold text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                        @foreach($indicators as $index => $indicator)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $indicator->name }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button @click="openEdit({{ $indicator->id }}, '{{ addslashes($indicator->name) }}')" type="button"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-school-blue hover:bg-school-blue/10 dark:hover:bg-school-blue/20 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form action="{{ route('evaluation-indicators.destroy', $indicator) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus indikator ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-500/15 transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card List --}}
            <div class="sm:hidden divide-y divide-gray-100 dark:divide-amoled-border">
                @foreach($indicators as $index => $indicator)
                    <div class="px-4 py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-7 h-7 rounded-lg bg-school-blue/10 flex items-center justify-center text-school-blue text-xs font-bold flex-shrink-0">{{ $index + 1 }}</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $indicator->name }}</span>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button @click="openEdit({{ $indicator->id }}, '{{ addslashes($indicator->name) }}')" type="button"
                                    class="w-8 h-8 rounded-lg text-school-blue hover:bg-school-blue/10 dark:hover:bg-school-blue/20 transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form action="{{ route('evaluation-indicators.destroy', $indicator) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus indikator ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-500/15 transition-colors flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Add/Edit Modal --}}
    <div x-show="showModal" class="fixed inset-0 z-[100]"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-black/70" @click="closeModal()"></div>

            <div class="relative w-full sm:max-w-md bg-white dark:bg-amoled-surface rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-amoled-border"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                 x-transition:enter-end="translate-y-0 scale-100 opacity-100">
                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="formMethod === 'PUT'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-amoled-border flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white" x-text="editMode ? 'Edit Indikator' : 'Tambah Indikator'"></h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="editMode ? 'Ubah nama indikator penilaian' : 'Masukkan nama indikator penilaian baru'"></p>
                        </div>
                        <button type="button" @click="closeModal()" class="p-1.5 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/20 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-4 sm:p-5">
                        <label for="indicator-name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            Nama Indikator <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="indicator-name" name="name" required x-model="indicatorName"
                               placeholder="Contoh: Kedisiplinan"
                               class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-3 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                    </div>

                    <div class="p-4 sm:p-5 border-t border-gray-100 dark:border-amoled-border bg-gray-50/50 dark:bg-white/[0.01] grid grid-cols-2 gap-2">
                        <button type="button" @click="closeModal()"
                                class="h-10 rounded-xl bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-300 dark:hover:bg-white/20 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="h-10 rounded-xl bg-school-blue hover:bg-school-blue/90 text-white font-bold text-sm transition-colors"
                                x-text="editMode ? 'Simpan Perubahan' : 'Tambah'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
