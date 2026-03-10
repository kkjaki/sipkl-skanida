@extends('layouts.app')

@section('content')
<div x-data="{
    showModal: false,
    selectedIndustry: null,
    selectedStudents: [],
    limit: 0,
    searchQuery: '',
    industrySearch: '',
    filterStatus: 'all',
    industries: @js($industries),
    candidates: @js($candidates),
    deleteMode: false,
    selectedInterns: [],
    transferStartDate: '',

    openModal(industry) {
        this.selectedIndustry = industry;
        this.limit = industry.remaining_quota;
        this.selectedStudents = [];
        this.searchQuery = '';
        this.deleteMode = false;
        this.selectedInterns = [];
        this.showModal = true;
        document.body.classList.add('overflow-hidden');
    },

    closeModal() {
        this.showModal = false;
        this.transferStartDate = '';
        document.body.classList.remove('overflow-hidden');
    },

    toggleStudent(studentId) {
        if (this.selectedStudents.includes(studentId)) {
            this.selectedStudents = this.selectedStudents.filter(id => id !== studentId);
        } else if (this.selectedStudents.length < this.limit) {
            this.selectedStudents.push(studentId);
        }
    },

    // Mode seleksi: 'none' | 'regular' | 'transfer'
    // Begitu 1 siswa reguler dipilih → semua pindahan disabled, dan sebaliknya.
    get selectionMode() {
        if (this.selectedStudents.length === 0) return 'none';
        const isTransfer = this.transferCandidates.some(s => s.user_id === this.selectedStudents[0]);
        return isTransfer ? 'transfer' : 'regular';
    },

    isDisabled(studentId) {
        // Kuota penuh & belum dipilih
        if (this.selectedStudents.length >= this.limit && !this.selectedStudents.includes(studentId)) return true;
        // Mutual exclusivity: disable opposite group
        if (this.selectionMode === 'regular' && this.transferCandidates.some(s => s.user_id === studentId)) return true;
        if (this.selectionMode === 'transfer' && this.regularCandidates.some(s => s.user_id === studentId)) return true;
        return false;
    },

    toggleInternDelete(internId) {
        if (this.selectedInterns.includes(internId)) {
            this.selectedInterns = this.selectedInterns.filter(id => id !== internId);
        } else {
            this.selectedInterns.push(internId);
        }
    },

    toggleAllInterns() {
        if (!this.selectedIndustry) return;
        const allIds = this.selectedIndustry.current_interns.map(i => i.id);
        if (this.selectedInterns.length === allIds.length) {
            this.selectedInterns = [];
        } else {
            this.selectedInterns = [...allIds];
        }
    },

    get filteredCandidates() {
        if (!this.searchQuery.trim()) return this.candidates;
        const q = this.searchQuery.toLowerCase();
        return this.candidates.filter(s =>
            s.user.name.toLowerCase().includes(q) ||
            s.nis.toLowerCase().includes(q) ||
            s.class_name.toLowerCase().includes(q)
        );
    },

    get regularCandidates() {
        return this.filteredCandidates.filter(s => !s.is_transfer);
    },

    get transferCandidates() {
        return this.filteredCandidates.filter(s => s.is_transfer);
    },

    get hasSelectedTransfer() {
        return this.selectedStudents.some(id =>
            this.transferCandidates.some(s => s.user_id === id)
        );
    },

    get filteredIndustries() {
        let result = this.industries;
        if (this.industrySearch.trim()) {
            const q = this.industrySearch.toLowerCase();
            result = result.filter(i =>
                i.name.toLowerCase().includes(q) ||
                i.city.toLowerCase().includes(q) ||
                (i.address && i.address.toLowerCase().includes(q))
            );
        }
        if (this.filterStatus === 'available') {
            result = result.filter(i => i.remaining_quota > 0);
        } else if (this.filterStatus === 'full') {
            result = result.filter(i => i.remaining_quota <= 0);
        }
        return result;
    }
}" class="space-y-6">

    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Penempatan Siswa (Plotting)</h1>
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
                   x-model="industrySearch"
                   placeholder="Cari industri berdasarkan nama, kota, atau alamat..."
                   class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue">
        </div>
        <select x-model="filterStatus"
                class="h-11 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer min-w-[160px]">
            <option value="all">Semua Status</option>
            <option value="available">Tersedia</option>
            <option value="full">Penuh</option>
        </select>
    </div>

    <!-- Industry Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <template x-for="industry in filteredIndustries" :key="industry.id">
            <div class="group flex flex-col bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-school-blue/5 hover:-translate-y-1">
                <div class="p-5 sm:p-6 flex-1">
                    <div class="flex justify-between items-start gap-3 mb-4 sm:mb-6">
                        <div class="p-2.5 sm:p-3 rounded-2xl bg-school-blue/5 dark:bg-school-blue/10 text-school-blue">
                             <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3m2 0V5m12 0v16m-5-14h.01M9 7h.01M9 11h.01M12 11h.01M9 15h.01M12 15h.01"/></svg>
                        </div>
                        <span :class="industry.remaining_quota > 0 ? 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-500' : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-500'"
                              class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                              x-text="industry.remaining_quota > 0 ? 'Tersedia' : 'Penuh'">
                        </span>
                    </div>

                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-1.5 sm:mb-2 leading-tight" x-text="industry.name"></h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="line-clamp-2" x-text="(industry.address ? industry.address + ', ' : '') + industry.city"></span>
                    </p>
                    <div class="mb-4 sm:mb-6"></div>

                    <div class="flex items-center justify-between p-3 sm:p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-amoled-border">
                        <div class="text-center flex-1 border-r border-gray-100 dark:border-amoled-border">
                            <p class="text-[10px] uppercase font-bold text-gray-400 dark:text-amoled-text tracking-widest mb-1">Terisi</p>
                            <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white" x-text="industry.interns_count"></p>
                        </div>
                        <div class="text-center flex-1">
                            <p class="text-[10px] uppercase font-bold text-gray-400 dark:text-amoled-text tracking-widest mb-1">Total Kuota</p>
                            <p class="text-base sm:text-lg font-bold text-gray-900 dark:text-white" x-text="industry.quota"></p>
                        </div>
                    </div>
                </div>

                <div class="px-5 sm:px-6 py-3 sm:py-4 border-t border-gray-100 dark:border-amoled-border">
                    <div :title="!industry.has_active_mou ? 'Industri ini tidak dapat di-plot karena tidak memiliki MoU aktif.' : ''">
                        <button @click="industry.has_active_mou && openModal(industry)"
                                :disabled="!industry.has_active_mou"
                                :class="industry.has_active_mou
                                    ? 'bg-school-blue hover:bg-school-blue-dark cursor-pointer group-hover:scale-[1.02] active:scale-[0.98]'
                                    : 'bg-gray-300 dark:bg-white/10 text-gray-400 dark:text-gray-500 cursor-not-allowed'"
                                class="w-full py-2.5 sm:py-3 px-4 rounded-2xl text-white font-bold text-sm transition-all shadow-lg shadow-school-blue/10 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <span x-text="industry.has_active_mou ? 'Kelola Plotting' : 'Tidak Ada MoU Aktif'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State: No Industries -->
    <template x-if="industries.length === 0">
        <div class="flex flex-col items-center justify-center py-16 sm:py-20 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-3xl">
            <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 mb-4">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3m2 0V5m12 0v16m-5-14h.01M9 7h.01M9 11h.01M12 11h.01M9 15h.01M12 15h.01"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tidak Ada Industri</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center">Belum ada industri yang dialokasikan untuk jurusan Anda di tahun ajaran ini.</p>
        </div>
    </template>

    <!-- Empty State: Search/Filter No Results -->
    <template x-if="industries.length > 0 && filteredIndustries.length === 0">
        <div class="flex flex-col items-center justify-center py-16 sm:py-20 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-3xl">
            <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 mb-4">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tidak Ditemukan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center">Tidak ada industri yang cocok dengan pencarian atau filter Anda.</p>
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

        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/80 backdrop-blur-md" @click="closeModal()"></div>

            <!-- Modal Content -->
            <div class="relative w-full sm:max-w-2xl bg-white dark:bg-amoled-surface rounded-2xl sm:rounded-[2rem] shadow-2xl overflow-hidden border border-gray-200 dark:border-amoled-border max-h-[90vh] flex flex-col"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-10 sm:scale-90"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                <!-- Modal Header -->
                <div class="p-5 sm:p-8 border-b border-gray-100 dark:border-amoled-border flex items-start justify-between gap-3 flex-shrink-0">
                    <div class="min-w-0 flex-1">
                        <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight truncate" x-text="selectedIndustry ? selectedIndustry.name : ''"></h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1" x-text="selectedIndustry ? ((selectedIndustry.address ? selectedIndustry.address + ', ' : '') + selectedIndustry.city) : ''"></p>
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
                            <h3 class="text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.2em]">Siswa Terplot</h3>
                            <div class="flex items-center gap-2">
                                <template x-if="selectedIndustry && selectedIndustry.current_interns.length > 0">
                                    <button @click="deleteMode = !deleteMode; selectedInterns = []"
                                            class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg transition-all"
                                            :class="deleteMode ? 'bg-red-500/10 text-red-500' : 'bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-red-500/10 hover:text-red-500'">
                                        <span x-text="deleteMode ? 'Batal' : 'Hapus Bulk'"></span>
                                    </button>
                                </template>
                                <span class="px-2 py-0.5 rounded-lg bg-gray-100 dark:bg-white/5 text-[10px] font-bold text-gray-500 dark:text-gray-400" x-text="selectedIndustry ? selectedIndustry.current_interns.length : 0"></span>
                            </div>
                        </div>

                        <!-- Select All (bulk delete mode) -->
                        <div x-show="deleteMode && selectedIndustry && selectedIndustry.current_interns.length > 1" class="mb-3" x-cloak>
                            <label @click="toggleAllInterns()" class="flex items-center gap-3 p-3 rounded-xl bg-red-500/5 dark:bg-red-500/10 border border-red-500/20 cursor-pointer">
                                <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all"
                                     :class="selectedIndustry && selectedInterns.length === selectedIndustry.current_interns.length ? 'bg-red-500 border-red-500' : 'border-gray-300 dark:border-amoled-border'">
                                    <svg x-show="selectedIndustry && selectedInterns.length === selectedIndustry.current_interns.length" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-bold text-red-600 dark:text-red-400">Pilih Semua</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-2 sm:gap-3">
                            <template x-if="selectedIndustry && selectedIndustry.current_interns.length > 0">
                                <template x-for="intern in selectedIndustry.current_interns" :key="intern.id">
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
                                                <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate" x-text="intern.student.class_name + (intern.student.address ? ' • ' + intern.student.address : '')"></p>
                                            </div>
                                        </div>
                                        <!-- Single delete button (non-bulk mode) -->
                                        <template x-if="!deleteMode">
                                            <form :action="'{{ url('placements') }}/' + intern.id" method="POST" onsubmit="return confirm('Hapus penempatan siswa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 sm:p-2.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/15 rounded-xl transition-all opacity-0 group-hover/intern:opacity-100 flex-shrink-0">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </template>
                                    </div>
                                </template>
                            </template>
                            <template x-if="!selectedIndustry || selectedIndustry.current_interns.length === 0">
                                <div class="p-6 sm:p-8 text-center border-2 border-dashed border-gray-100 dark:border-amoled-border rounded-3xl">
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic font-medium">Belum ada siswa yang di-plot ke industri ini.</p>
                                </div>
                            </template>
                        </div>

                        <!-- Bulk Delete Action -->
                        <div x-show="deleteMode && selectedInterns.length > 0" class="mt-3" x-cloak>
                            <form action="{{ route('placements.destroyBulk') }}" method="POST" onsubmit="return confirm('Hapus ' + this.querySelector('[data-count]').dataset.count + ' penempatan siswa? Siswa akan kembali ke daftar kandidat.')">
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
                            <h3 class="text-[11px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.2em]">Pilih Kandidat</h3>
                            <span class="px-2 py-0.5 rounded-lg bg-school-blue/10 text-[10px] font-bold text-school-blue" x-text="filteredCandidates.length"></span>
                        </div>

                        <!-- Search -->
                        <div class="relative mb-3 sm:mb-4">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text"
                                   x-model="searchQuery"
                                   placeholder="Cari nama, NIS, atau kelas..."
                                   class="w-full pl-10 pr-4 py-2.5 sm:py-3 text-sm rounded-2xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-amoled-input text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-school-blue/30 focus:border-school-blue transition-colors">
                        </div>

                        <div class="grid grid-cols-1 gap-2 sm:gap-3">

                            {{-- Siswa Reguler --}}
                            <template x-if="regularCandidates.length > 0">
                                <p class="text-[10px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.2em] px-1">Siswa Reguler</p>
                            </template>
                            <template x-for="student in regularCandidates" :key="student.user_id">
                                <label class="relative flex items-center p-3 sm:p-4 rounded-2xl border transition-all cursor-pointer group/label"
                                       :class="[
                                           selectedStudents.includes(student.user_id)
                                               ? 'border-school-blue bg-school-blue/5 dark:bg-school-blue/10 ring-2 ring-school-blue/20'
                                               : 'border-gray-100 dark:border-amoled-border hover:border-school-blue/30 dark:hover:bg-white/[0.04]',
                                           isDisabled(student.user_id) ? 'opacity-30 cursor-not-allowed filter grayscale' : ''
                                       ]">
                                    <input type="checkbox" class="sr-only"
                                           :value="student.user_id"
                                           :checked="selectedStudents.includes(student.user_id)"
                                           :disabled="isDisabled(student.user_id)"
                                           @change="toggleStudent(student.user_id)">
                                    <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-gray-400 font-black text-sm sm:text-base flex-shrink-0"
                                             :class="selectedStudents.includes(student.user_id) ? 'bg-school-blue/20 text-school-blue' : ''"
                                             x-text="student.user.name.charAt(0)"></div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="student.user.name"></p>
                                            <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate" x-text="student.class_name + (student.address ? ' • ' + student.address : '')"></p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full border-2 flex items-center justify-center transition-all duration-300 flex-shrink-0"
                                         :class="selectedStudents.includes(student.user_id) ? 'bg-school-blue border-school-blue scale-110' : 'border-gray-300 dark:border-amoled-border group-hover/label:border-school-blue/50'">
                                        <svg x-show="selectedStudents.includes(student.user_id)" class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </label>
                            </template>

                            {{-- Divider: Siswa Pindahan --}}
                            <template x-if="transferCandidates.length > 0">
                                <div class="flex items-center gap-3 py-1">
                                    <div class="flex-1 h-px bg-amber-200 dark:bg-amber-500/30"></div>
                                    <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase tracking-[0.2em] flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        Siswa Pindahan
                                    </span>
                                    <div class="flex-1 h-px bg-amber-200 dark:bg-amber-500/30"></div>
                                </div>
                            </template>
                            <template x-for="student in transferCandidates" :key="student.user_id">
                                <label class="relative flex items-center p-3 sm:p-4 rounded-2xl border transition-all cursor-pointer group/label"
                                       :class="[
                                           selectedStudents.includes(student.user_id)
                                               ? 'border-amber-500 bg-amber-500/5 dark:bg-amber-500/10 ring-2 ring-amber-500/20'
                                               : 'border-amber-200 dark:border-amber-500/20 hover:border-amber-400/50 dark:hover:bg-amber-500/5',
                                           isDisabled(student.user_id) ? 'opacity-30 cursor-not-allowed filter grayscale' : ''
                                       ]">
                                    <input type="checkbox" class="sr-only"
                                           :value="student.user_id"
                                           :checked="selectedStudents.includes(student.user_id)"
                                           :disabled="isDisabled(student.user_id)"
                                           @change="toggleStudent(student.user_id)">
                                    <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-amber-100 dark:bg-amber-500/15 flex items-center justify-center text-amber-700 dark:text-amber-400 font-black text-sm sm:text-base flex-shrink-0"
                                             x-text="student.user.name.charAt(0)"></div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="student.user.name"></p>
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 flex-shrink-0">Pindahan</span>
                                            </div>
                                            <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate" x-text="student.class_name + (student.address ? ' • ' + student.address : '')"></p>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full border-2 flex items-center justify-center transition-all duration-300 flex-shrink-0"
                                         :class="selectedStudents.includes(student.user_id) ? 'bg-amber-500 border-amber-500 scale-110' : 'border-amber-300 dark:border-amber-500/40 group-hover/label:border-amber-400'">
                                        <svg x-show="selectedStudents.includes(student.user_id)" class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </label>
                            </template>

                            <template x-if="candidates.length === 0">
                                <div class="p-6 sm:p-8 text-center border-2 border-dashed border-gray-100 dark:border-amoled-border rounded-3xl">
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic font-medium">Semua siswa sudah memiliki penempatan.</p>
                                </div>
                            </template>

                            <template x-if="candidates.length > 0 && filteredCandidates.length === 0">
                                <div class="p-6 sm:p-8 text-center border-2 border-dashed border-gray-100 dark:border-amoled-border rounded-3xl">
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic font-medium">Tidak ditemukan kandidat yang cocok.</p>
                                </div>
                            </template>
                        </div>

                        {{-- Input tanggal mulai untuk siswa pindahan --}}
                        <div x-show="hasSelectedTransfer" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mt-4 p-4 rounded-2xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30">
                            <label class="block text-xs font-black text-amber-700 dark:text-amber-400 uppercase tracking-[0.15em] mb-2">
                                <svg class="inline w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tanggal Mulai Pindahan
                            </label>
                            <input type="date" name="transfer_start_date"
                                   x-model="transferStartDate"
                                   max="{{ today()->toDateString() }}"
                                   class="w-full px-3 py-2 text-sm rounded-xl border border-amber-200 dark:border-amber-500/30 bg-white dark:bg-amoled-surface text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
                            <p class="mt-1.5 text-[11px] text-amber-600 dark:text-amber-400/70">
                                Jika dikosongkan, tanggal mulai akan diisi otomatis dengan waktu saat data disimpan.
                            </p>
                        </div>
                    </div>

                    <template x-if="limit <= 0 && (selectedIndustry && selectedIndustry.current_interns.length >= selectedIndustry.quota)">
                        <div class="p-5 sm:p-6 rounded-3xl bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-100 dark:border-yellow-500/20 text-yellow-800 dark:text-yellow-400 text-sm flex items-start gap-3 sm:gap-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="font-bold mb-1 uppercase tracking-tight text-xs">Kuota Penuh</p>
                                <p class="leading-relaxed opacity-90">Industri ini sudah mencapai batas kuota maksimal. Anda tidak dapat menambahkan siswa lagi.</p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="p-5 sm:p-8 border-t border-gray-100 dark:border-amoled-border bg-gray-50/50 dark:bg-white/[0.01] flex items-center justify-between flex-shrink-0">
                    <div class="flex flex-col">
                        <p class="text-[10px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.2em] mb-1">Status Pilihan</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                            Terpilih: <span class="text-school-blue" x-text="selectedStudents.length"></span> <span class="text-gray-400">/</span> <span x-text="limit"></span> Siswa
                        </p>
                    </div>

                    <form action="{{ route('placements.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="industry_id" :value="selectedIndustry ? selectedIndustry.id : ''">
                        <template x-for="id in selectedStudents" :key="'form-'+id">
                            <input type="hidden" name="student_ids[]" :value="id">
                        </template>
                        {{-- Mirror transferStartDate dari modal body ke form --}}
                        <input type="hidden" name="transfer_start_date" :value="transferStartDate">
                        {{-- transfer_start_date dibaca langsung dari input di body modal --}}
                        <button type="submit"
                                :disabled="selectedStudents.length === 0"
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
