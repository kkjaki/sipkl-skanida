@extends('layouts.app')

@section('content')
<div x-data="{
    search: '',
    departmentId: '',
    industryId: '',
    internships: @js($internships),
    withdrawModal: false,
    withdrawId: null,
    withdrawName: '',

    openWithdrawModal(id, name) {
        this.withdrawId = id;
        this.withdrawName = name;
        this.withdrawModal = true;
        document.body.classList.add('overflow-hidden');
    },

    closeWithdrawModal() {
        this.withdrawModal = false;
        this.withdrawId = null;
        this.withdrawName = '';
        document.body.classList.remove('overflow-hidden');
    },

    get filteredInternships() {
        let result = this.internships;
        
        if (this.search.trim()) {
            const q = this.search.toLowerCase();
            result = result.filter(i => {
                const name = (i.student?.user?.name || '').toLowerCase();
                const nis = (i.student?.nis || '').toLowerCase();
                const className = (i.student?.class_name || '').toLowerCase();
                return name.includes(q) || nis.includes(q) || className.includes(q);
            });
        }
        
        if (this.departmentId) {
            result = result.filter(i => i.student?.department_id == this.departmentId);
        }
        
        if (this.industryId) {
            result = result.filter(i => i.industry_id == this.industryId);
        }
        
        return result;
    }
}" class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">PKL Aktif</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-amoled-text">
                Daftar siswa yang sedang menjalani PKL. Gunakan fitur <span class="font-semibold text-amber-600 dark:text-amber-400">Pindahkan Lokasi</span> untuk merotasi siswa ke industri baru.
            </p>
        </div>
        <div class="flex-shrink-0">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 text-sm font-bold border border-emerald-500/20">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                <span><span x-text="filteredInternships.length"></span> Siswa Aktif</span>
            </span>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-700 dark:text-red-400 text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white dark:bg-amoled-surface border border-gray-200 dark:border-amoled-border rounded-3xl p-5 flex flex-col sm:flex-row gap-3">

        {{-- Search --}}
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search"
                   placeholder="Cari nama, NIS, atau kelas..."
                   class="w-full pl-10 pr-4 py-2.5 text-sm rounded-2xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-amoled-input text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-school-blue/30 focus:border-school-blue transition-colors">
        </div>

        @if ($departmentId === null)
        {{-- Filter Jurusan (Admin only) --}}
        <select x-model="departmentId"
                class="px-4 py-2.5 text-sm rounded-2xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-amoled-input text-gray-900 dark:text-white focus:ring-2 focus:ring-school-blue/30 focus:border-school-blue transition-colors">
            <option value="">Semua Jurusan</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}">
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
        @endif

        {{-- Filter Industri --}}
        <select x-model="industryId"
                class="px-4 py-2.5 text-sm rounded-2xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-amoled-input text-gray-900 dark:text-white focus:ring-2 focus:ring-school-blue/30 focus:border-school-blue transition-colors">
            <option value="">Semua Industri</option>
            @foreach ($industries as $industry)
                <option value="{{ $industry->id }}">
                    {{ $industry->name }}
                </option>
            @endforeach
        </select>

        <button x-show="search || departmentId || industryId" x-cloak
                @click="search=''; departmentId=''; industryId=''"
                class="px-5 py-2.5 rounded-2xl border border-gray-200 dark:border-amoled-border text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white text-sm font-medium transition-colors text-center">
            Reset
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-amoled-surface border border-gray-200 dark:border-amoled-border rounded-3xl overflow-hidden">
        
        <template x-if="internships.length === 0">
            <div class="p-12 sm:p-16 text-center">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm font-semibold text-gray-400 dark:text-gray-500">Tidak ada siswa PKL aktif ditemukan.</p>
            </div>
        </template>

        <template x-if="internships.length > 0 && filteredInternships.length === 0">
            <div class="p-12 sm:p-16 text-center">
                <p class="text-sm font-semibold text-gray-400 dark:text-gray-500">Tidak ditemukan hasil sesuai filter.</p>
            </div>
        </template>

        <template x-if="filteredInternships.length > 0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-amoled-border">
                            <th class="px-5 py-4 text-left text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em]">Siswa</th>
                            @if ($departmentId === null)
                            <th class="px-5 py-4 text-left text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em]">Jurusan</th>
                            @endif
                            <th class="px-5 py-4 text-left text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em]">Industri</th>
                            <th class="px-5 py-4 text-left text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em] hidden lg:table-cell">Alamat Industri</th>
                            <th class="px-5 py-4 text-left text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em] hidden md:table-cell">Guru Pembimbing</th>
                            <th class="px-5 py-4 text-left text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em] hidden sm:table-cell">Tgl Mulai</th>
                            <th class="px-5 py-4 text-right text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-amoled-border">
                        <template x-for="internship in filteredInternships" :key="internship.id">
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors group">
                            {{-- Siswa --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-school-blue/10 dark:bg-school-blue/20 flex items-center justify-center text-school-blue font-black text-sm flex-shrink-0"
                                         x-text="(internship.student?.user?.name || '?').substring(0, 1).toUpperCase()">
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white" x-text="internship.student?.user?.name || '-'"></p>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500"><span x-text="internship.student?.nis || '-'"></span> · <span x-text="internship.student?.class_name || '-'"></span></p>
                                    </div>
                                </div>
                            </td>

                            @if ($departmentId === null)
                            {{-- Jurusan --}}
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 text-xs font-bold" x-text="internship.student?.department?.name || '-'">
                                </span>
                            </td>
                            @endif

                            {{-- Industri --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-900 dark:text-white truncate max-w-[180px]" x-text="internship.industry?.name || '-'"></p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500" x-text="internship.industry?.city || '-'"></p>
                            </td>

                            {{-- Alamat Industri --}}
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <p class="text-[12px] text-gray-500 dark:text-gray-400 truncate max-w-[200px]" x-text="internship.industry?.address || '-'">
                                </p>
                            </td>

                            {{-- Guru Pembimbing --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                <template x-if="internship.supervisor">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="internship.supervisor?.user?.name"></p>
                                </template>
                                <template x-if="!internship.supervisor">
                                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">Belum ditetapkan</span>
                                </template>
                            </td>

                            {{-- Tgl Mulai --}}
                            <td class="px-5 py-4 hidden sm:table-cell">
                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="internship.formatted_start_date || '-'">
                                </p>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 text-right">
                                <button type="button"
                                        @click="openWithdrawModal(internship.id, internship.student?.user?.name || '')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-xs font-bold border border-amber-500/20 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    Pindahkan
                                </button>
                            </td>
                        </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </template>
    </div>

    {{-- Withdraw Confirmation Modal --}}
    <div x-show="withdrawModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[99] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         @click.self="closeWithdrawModal()">

        <div x-show="withdrawModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white dark:bg-amoled-surface rounded-3xl shadow-2xl border border-gray-200 dark:border-amoled-border w-full max-w-md">

            <div class="p-6 sm:p-8">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Pindahkan Lokasi Siswa</h3>
                <p class="text-sm text-gray-500 dark:text-amoled-text mb-1">
                    Anda akan memindahkan lokasi PKL:
                </p>
                <p class="text-sm font-bold text-gray-900 dark:text-white mb-4" x-text="withdrawName"></p>
                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
                    Status PKL siswa ini akan berubah menjadi <strong>Withdrawn</strong> dan tanggal selesai akan diisi otomatis hari ini. Siswa akan kembali muncul sebagai <strong>Kandidat Pindahan</strong> di halaman Plotting Siswa.
                </div>
            </div>

            <div class="px-6 sm:px-8 pb-6 sm:pb-8 flex gap-3">
                <button type="button"
                        @click="closeWithdrawModal()"
                        class="flex-1 py-3 px-4 rounded-2xl border border-gray-200 dark:border-amoled-border text-gray-700 dark:text-gray-300 font-bold text-sm transition-colors hover:bg-gray-50 dark:hover:bg-white/5">
                    Batal
                </button>
                <form :action="`{{ url('internships') }}/${withdrawId}/withdraw`" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="w-full py-3 px-4 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-black text-sm transition-all shadow-lg shadow-amber-500/20">
                        Ya, Pindahkan
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
