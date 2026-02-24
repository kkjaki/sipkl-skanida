@extends('layouts.app')

@section('content')
<div x-data="{
    showModal: false,
    selectedSupervisor: null,
    selectedInternships: [],
    limit: 0,
    searchQuery: '',
    supervisorSearch: '',
    filterStatus: 'all',
    supervisors: @js($supervisors),
    candidates: @js($candidates),
    deleteMode: false,
    selectedInterns: [],

    openModal(supervisor) {
        this.selectedSupervisor = supervisor;
        this.limit = supervisor.remaining_quota;
        this.selectedInternships = [];
        this.searchQuery = '';
        this.deleteMode = false;
        this.selectedInterns = [];
        this.showModal = true;
        document.body.classList.add('overflow-hidden');
    },

    closeModal() {
        this.showModal = false;
        document.body.classList.remove('overflow-hidden');
    },

    toggleInternship(internshipId) {
        if (this.selectedInternships.includes(internshipId)) {
            this.selectedInternships = this.selectedInternships.filter(id => id !== internshipId);
        } else if (this.selectedInternships.length < this.limit) {
            this.selectedInternships.push(internshipId);
        }
    },

    isDisabled(internshipId) {
        return this.selectedInternships.length >= this.limit && !this.selectedInternships.includes(internshipId);
    },

    toggleInternDelete(internId) {
        if (this.selectedInterns.includes(internId)) {
            this.selectedInterns = this.selectedInterns.filter(id => id !== internId);
        } else {
            this.selectedInterns.push(internId);
        }
    },

    toggleAllInterns() {
        if (!this.selectedSupervisor) return;
        const allIds = this.selectedSupervisor.current_interns.map(i => i.id);
        if (this.selectedInterns.length === allIds.length) {
            this.selectedInterns = [];
        } else {
            this.selectedInterns = [...allIds];
        }
    },

    get filteredCandidates() {
        if (!this.searchQuery.trim()) return this.candidates;
        const q = this.searchQuery.toLowerCase();
        return this.candidates.filter(c =>
            c.student.user.name.toLowerCase().includes(q) ||
            c.student.nis.toLowerCase().includes(q) ||
            c.student.class_name.toLowerCase().includes(q) ||
            (c.industry && c.industry.name.toLowerCase().includes(q))
        );
    },

    get filteredSupervisors() {
        let result = this.supervisors;
        if (this.supervisorSearch.trim()) {
            const q = this.supervisorSearch.toLowerCase();
            result = result.filter(s =>
                s.user.name.toLowerCase().includes(q) ||
                (s.nip && s.nip.toLowerCase().includes(q))
            );
        }
        if (this.filterStatus === 'available') {
            result = result.filter(s => s.remaining_quota > 0);
        } else if (this.filterStatus === 'full') {
            result = result.filter(s => s.remaining_quota <= 0);
        }
        return result;
    }
}" class="space-y-6">

    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Plotting Guru Pembimbing</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Tahun Ajaran Aktif: <span class="font-medium text-school-blue">{{ $activeYear->name }}</span></p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="flex items-center p-4 text-sm text-green-800 border border-green-300 rounded-xl bg-green-50 dark:bg-green-500/10 dark:text-green-400 dark:border-green-800/50" role="alert">
            <svg class="flex-shrink-0 w-4 h-4 mr-3" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="flex items-center p-4 text-sm text-red-800 border border-red-300 rounded-xl bg-red-50 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800/50" role="alert">
            <svg class="flex-shrink-0 w-4 h-4 mr-3" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
            </svg>
            <div><span class="font-bold">Gagal!</span> {{ session('error') }}</div>
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text"
                   x-model="supervisorSearch"
                   placeholder="Cari guru berdasarkan nama atau NIP..."
                   class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue">
        </div>
        <select x-model="filterStatus"
                class="h-11 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer min-w-[160px]">
            <option value="all">Semua Status</option>
            <option value="available">Tersedia</option>
            <option value="full">Penuh</option>
        </select>
    </div>

    <!-- Supervisor Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <template x-for="supervisor in filteredSupervisors" :key="supervisor.user_id">
            <div class="group flex flex-col bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-school-blue/5 hover:-translate-y-1">
                <div class="p-5 sm:p-6 flex-1">
                    <div class="flex justify-between items-start gap-3 mb-4 sm:mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-school-blue/10 flex items-center justify-center text-school-blue font-black text-lg sm:text-xl flex-shrink-0" x-text="supervisor.user.name.charAt(0)"></div>
                        <span :class="supervisor.remaining_quota > 0 ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-500' : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-500'"
                              class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                              x-text="supervisor.remaining_quota > 0 ? 'Tersedia' : 'Penuh'">
                        </span>
                    </div>

                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-1 leading-tight" x-text="supervisor.user.name"></h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4 sm:mb-6 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        <span x-text="supervisor.nip ? 'NIP: ' + supervisor.nip : 'NIP: -'"></span>
                    </p>

                    <div class="flex items-center justify-between p-3 sm:p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-amoled-border">
                        <div class="text-center flex-1 border-r border-gray-100 dark:border-amoled-border">
                            <p class="text-[10px] uppercase font-bold text-gray-400 dark:text-amoled-text tracking-widest mb-1">Terbimbing</p>
                            <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white" x-text="supervisor.interns_count"></p>
                        </div>
                        <div class="text-center flex-1">
                            <p class="text-[10px] uppercase font-bold text-gray-400 dark:text-amoled-text tracking-widest mb-1">Total Kuota</p>
                            <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white" x-text="supervisor.quota"></p>
                        </div>
                    </div>
                </div>

                <div class="px-5 sm:px-6 py-3 sm:py-4 border-t border-gray-100 dark:border-amoled-border">
                    <button @click="openModal(supervisor)"
                            class="w-full py-2.5 sm:py-3 px-4 rounded-2xl bg-school-blue hover:bg-school-blue-dark text-white font-bold text-sm transition-all shadow-lg shadow-school-blue/10 flex items-center justify-center gap-2 group-hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Kelola Bimbingan
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State: No Supervisors -->
    <template x-if="supervisors.length === 0">
        <div class="flex flex-col items-center justify-center py-16 sm:py-20 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-3xl">
            <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 mb-4">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tidak Ada Guru Pembimbing</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center">Belum ada guru pembimbing yang dialokasikan untuk jurusan Anda di tahun ajaran ini.</p>
        </div>
    </template>

    <!-- Empty State: Search/Filter No Results -->
    <template x-if="supervisors.length > 0 && filteredSupervisors.length === 0">
        <div class="flex flex-col items-center justify-center py-16 sm:py-20 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-3xl">
            <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 mb-4">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tidak Ditemukan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center">Tidak ada guru yang cocok dengan pencarian atau filter Anda.</p>
        </div>
    </template>

    <!-- Modal Container -->
    <div x-show="showModal"
         class="fixed inset-0 z-[100] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>

        <div class="flex items-end sm:items-center justify-center min-h-screen sm:p-6">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="closeModal()"></div>

            <!-- Modal Content -->
            <div class="relative w-full sm:max-w-2xl bg-white dark:bg-amoled-surface sm:rounded-[2rem] rounded-t-3xl shadow-2xl overflow-hidden border-t sm:border border-gray-200 dark:border-amoled-border max-h-[95vh] sm:max-h-[90vh] flex flex-col"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-10 sm:scale-90"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                <!-- Modal Header -->
                <div class="p-5 sm:p-8 border-b border-gray-100 dark:border-amoled-border flex items-start justify-between gap-3 flex-shrink-0">
                    <div class="min-w-0 flex-1">
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight truncate" x-text="selectedSupervisor ? selectedSupervisor.user.name : ''"></h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="selectedSupervisor && selectedSupervisor.nip ? 'NIP: ' + selectedSupervisor.nip : ''"></p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-xs font-bold text-school-blue uppercase tracking-wider" x-text="'Sisa Kuota: ' + limit + ' Siswa'"></span>
                        </div>
                    </div>
                    <button @click="closeModal()" class="p-2 rounded-xl bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-white hover:bg-gray-200 dark:hover:bg-white/20 transition-all flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 sm:p-8 overflow-y-auto custom-scrollbar flex-1">
                    <!-- Current Interns Section -->
                    <div class="mb-8 sm:mb-10">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <h3 class="text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.2em]">Siswa Terbimbing</h3>
                            <div class="flex items-center gap-2">
                                <template x-if="selectedSupervisor && selectedSupervisor.current_interns.length > 0">
                                    <button @click="deleteMode = !deleteMode; selectedInterns = []"
                                            class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg transition-all"
                                            :class="deleteMode ? 'bg-red-500/10 text-red-500' : 'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-red-500/10 hover:text-red-500'">
                                        <span x-text="deleteMode ? 'Batal' : 'Hapus Bulk'"></span>
                                    </button>
                                </template>
                                <span class="px-2 py-0.5 rounded-lg bg-gray-100 dark:bg-white/5 text-[10px] font-bold text-gray-500 dark:text-gray-400" x-text="selectedSupervisor ? selectedSupervisor.current_interns.length : 0"></span>
                            </div>
                        </div>

                        <!-- Select All (bulk delete mode) -->
                        <div x-show="deleteMode && selectedSupervisor && selectedSupervisor.current_interns.length > 1" class="mb-3" x-cloak>
                            <label @click="toggleAllInterns()" class="flex items-center gap-3 p-3 rounded-xl bg-red-500/5 dark:bg-red-500/10 border border-red-500/20 cursor-pointer">
                                <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all"
                                     :class="selectedSupervisor && selectedInterns.length === selectedSupervisor.current_interns.length ? 'bg-red-500 border-red-500' : 'border-gray-300 dark:border-amoled-border'">
                                    <svg x-show="selectedSupervisor && selectedInterns.length === selectedSupervisor.current_interns.length" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-bold text-red-600 dark:text-red-400">Pilih Semua</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-2 sm:gap-3">
                            <template x-if="selectedSupervisor && selectedSupervisor.current_interns.length > 0">
                                <template x-for="intern in selectedSupervisor.current_interns" :key="intern.id">
                                    <div class="flex items-center justify-between p-3 sm:p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-amoled-border group/intern">
                                        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1">
                                            <!-- Bulk delete checkbox -->
                                            <template x-if="deleteMode">
                                                <div @click="toggleInternDelete(intern.id)"
                                                     class="w-5 h-5 rounded border-2 flex items-center justify-center cursor-pointer transition-all flex-shrink-0"
                                                     :class="selectedInterns.includes(intern.id) ? 'bg-red-500 border-red-500' : 'border-gray-300 dark:border-amoled-border hover:border-red-400'">
                                                    <svg x-show="selectedInterns.includes(intern.id)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            </template>
                                            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-school-blue/10 flex items-center justify-center text-school-blue font-black text-sm sm:text-lg flex-shrink-0" x-text="intern.student.user.name.charAt(0)"></div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="intern.student.user.name"></p>
                                                <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate" x-text="intern.student.class_name + (intern.industry ? ' • ' + intern.industry.name : '')"></p>
                                            </div>
                                        </div>
                                        <!-- Single delete button (non-bulk mode) -->
                                        <template x-if="!deleteMode">
                                            <form :action="'{{ url('supervisor-placements/bulk') }}'" method="POST" onsubmit="return confirm('Hapus pembimbing dari siswa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="internship_ids[]" :value="intern.id">
                                                <button type="submit" class="p-2 sm:p-2.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/15 rounded-xl transition-all opacity-0 group-hover/intern:opacity-100 flex-shrink-0">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </template>
                                    </div>
                                </template>
                            </template>
                            <template x-if="!selectedSupervisor || selectedSupervisor.current_interns.length === 0">
                                <div class="p-6 sm:p-8 text-center border-2 border-dashed border-gray-100 dark:border-amoled-border rounded-3xl">
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic font-medium">Belum ada siswa yang dibimbing oleh guru ini.</p>
                                </div>
                            </template>
                        </div>

                        <!-- Bulk Delete Action -->
                        <div x-show="deleteMode && selectedInterns.length > 0" class="mt-3" x-cloak>
                            <form action="{{ route('supervisor-placements.destroy') }}" method="POST" onsubmit="return confirm('Hapus ' + this.querySelector('[data-count]').dataset.count + ' penempatan pembimbing? Siswa akan kembali ke daftar kandidat.')">
                                @csrf
                                @method('DELETE')
                                <template x-for="id in selectedInterns" :key="'del-'+id">
                                    <input type="hidden" name="internship_ids[]" :value="id">
                                </template>
                                <button type="submit"
                                        :data-count="selectedInterns.length"
                                        data-count=""
                                        class="w-full py-3 rounded-2xl bg-red-500 hover:bg-red-600 text-white font-bold text-sm transition-all flex items-center justify-center gap-2 active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus <span x-text="selectedInterns.length"></span> Penempatan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Candidates Section -->
                    <div x-show="limit > 0">
                        <div class="flex items-center justify-between mb-3 sm:mb-4">
                            <h3 class="text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.2em]">Pilih Kandidat Siswa</h3>
                            <span class="px-2 py-0.5 rounded-lg bg-school-blue/10 text-[10px] font-bold text-school-blue" x-text="filteredCandidates.length"></span>
                        </div>

                        <!-- Search -->
                        <div class="relative mb-3 sm:mb-4">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text"
                                   x-model="searchQuery"
                                   placeholder="Cari nama, NIS, kelas, atau industri..."
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-amoled-input text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-school-blue/30 focus:border-school-blue transition-colors">
                        </div>

                        <div class="grid grid-cols-1 gap-2 sm:gap-3">
                            <template x-for="candidate in filteredCandidates" :key="candidate.id">
                                <label class="relative flex items-center p-3 sm:p-4 rounded-2xl border transition-all cursor-pointer group/label"
                                       :class="[
                                           selectedInternships.includes(candidate.id)
                                               ? 'border-school-blue bg-school-blue/5 dark:bg-school-blue/10 ring-2 ring-school-blue/20'
                                               : 'border-gray-100 dark:border-amoled-border hover:border-school-blue/30 dark:hover:bg-white/[0.04]',
                                           isDisabled(candidate.id) ? 'opacity-30 cursor-not-allowed filter grayscale' : ''
                                       ]">
                                    <input type="checkbox"
                                           class="sr-only"
                                           :value="candidate.id"
                                           :checked="selectedInternships.includes(candidate.id)"
                                           :disabled="isDisabled(candidate.id)"
                                           @change="toggleInternship(candidate.id)">

                                    <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-gray-400 font-black text-sm sm:text-base flex-shrink-0"
                                             :class="selectedInternships.includes(candidate.id) ? 'bg-school-blue/20 text-school-blue' : ''"
                                             x-text="candidate.student.user.name.charAt(0)"></div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="candidate.student.user.name"></p>
                                            <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate" x-text="candidate.student.class_name + (candidate.industry ? ' • ' + candidate.industry.name : '')"></p>
                                        </div>
                                    </div>

                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full border-2 flex items-center justify-center transition-all duration-300 flex-shrink-0"
                                         :class="selectedInternships.includes(candidate.id) ? 'bg-school-blue border-school-blue scale-110' : 'border-gray-300 dark:border-amoled-border group-hover/label:border-school-blue/50'">
                                        <svg x-show="selectedInternships.includes(candidate.id)" class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </label>
                            </template>

                            <template x-if="candidates.length === 0">
                                <div class="p-6 sm:p-8 text-center border-2 border-dashed border-gray-100 dark:border-amoled-border rounded-3xl">
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic font-medium">Semua siswa sudah memiliki guru pembimbing.</p>
                                </div>
                            </template>

                            <template x-if="candidates.length > 0 && filteredCandidates.length === 0">
                                <div class="p-6 sm:p-8 text-center border-2 border-dashed border-gray-100 dark:border-amoled-border rounded-3xl">
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic font-medium">Tidak ditemukan kandidat yang cocok.</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <template x-if="limit <= 0 && (selectedSupervisor && selectedSupervisor.current_interns.length >= selectedSupervisor.quota)">
                        <div class="p-5 sm:p-6 rounded-3xl bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-100 dark:border-yellow-500/20 text-yellow-800 dark:text-yellow-400 text-sm flex items-start gap-3 sm:gap-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="font-bold mb-1 uppercase tracking-tight text-xs">Kuota Penuh</p>
                                <p class="leading-relaxed opacity-90">Guru pembimbing ini sudah mencapai batas kuota maksimal. Anda tidak dapat menambahkan siswa lagi.</p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="p-5 sm:p-8 border-t border-gray-100 dark:border-amoled-border bg-gray-50/50 dark:bg-white/[0.01] flex items-center justify-between flex-shrink-0">
                    <div class="flex flex-col">
                        <p class="text-[10px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.2em] mb-1">Status Pilihan</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                            Terpilih: <span class="text-school-blue" x-text="selectedInternships.length"></span> <span class="text-gray-400">/</span> <span x-text="limit"></span> Siswa
                        </p>
                    </div>

                    <form action="{{ route('supervisor-placements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="supervisor_id" :value="selectedSupervisor ? selectedSupervisor.user_id : ''">
                        <template x-for="id in selectedInternships" :key="'form-'+id">
                            <input type="hidden" name="internship_ids[]" :value="id">
                        </template>
                        <button type="submit"
                                :disabled="selectedInternships.length === 0"
                                class="py-3 px-6 sm:py-4 sm:px-10 rounded-2xl bg-school-blue hover:bg-school-blue-dark text-white font-black text-xs sm:text-sm uppercase tracking-widest transition-all shadow-xl shadow-school-blue/20 disabled:opacity-30 disabled:cursor-not-allowed disabled:shadow-none hover:scale-[1.02] active:scale-[0.98]">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(156, 163, 175, 0.1);
        border-radius: 20px;
        border: 2px solid transparent;
        background-clip: content-box;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.2);
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>
@endpush
