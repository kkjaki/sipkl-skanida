@extends('layouts.app')

@section('content')

<div class="flex flex-col gap-6" x-data="{
    supervisors: @js($supervisors->map(fn($s) => [
        'user_id' => $s->user_id,
        'name' => $s->user->name,
        'nip' => $s->nip,
        'department_id' => $s->department_id,
        'department_name' => $s->department?->name,
        'department_code' => $s->department?->code,
    ])->values()),
    allocations: {
        @foreach ($supervisors as $supervisor)
        {{ $supervisor->user_id }}: {{ $supervisor->allocations->first()->quota ?? 0 }}, @endforeach
    },
    initialAllocations: {
            @foreach ($supervisors as $supervisor)
                {{ $supervisor->user_id }}: {{ $supervisor->allocations->first()->quota ?? 0 }}, @endforeach
        },
        isDirty: false,
        searchQuery: '',
        filterDepartment: 'all',
        filterStatus: 'all',
        checkDirty() {
            this.isDirty = JSON.stringify(this.allocations) !== JSON.stringify(this.initialAllocations);
        },
        submitForm() {
            if (confirm('Apakah Anda yakin ingin menyimpan perubahan kuota pembimbing?')) {
                // Set isDirty = false agar beforeunload tidak muncul
                this.isDirty = false;
                this.$refs.form.submit();
            }
        },
        get filteredSupervisors() {
            let result = this.supervisors;
            if (this.searchQuery.trim()) {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(s =>
                    s.name.toLowerCase().includes(q) ||
                    (s.nip && s.nip.toLowerCase().includes(q))
                );
            }
            if (this.filterDepartment !== 'all') {
                result = result.filter(s => String(s.department_id) === String(this.filterDepartment));
            }
            if (this.filterStatus === 'empty') {
                result = result.filter(s => !this.allocations[s.user_id] || this.allocations[s.user_id] == 0);
            } else if (this.filterStatus === 'filled') {
                result = result.filter(s => this.allocations[s.user_id] && this.allocations[s.user_id] > 0);
            }
            return result;
        },
        init() {
            // Peringatan saat akan meninggalkan halaman
            window.addEventListener('beforeunload', (e) => {
                if (this.isDirty) {
                    e.preventDefault();
                    e.returnValue = '';
                    return '';
                }
            });
        }
    }"
        @click.away="
        if (isDirty) {
            const links = document.querySelectorAll('a:not([target=_blank])');
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    if (this.isDirty && !link.href.includes('#')) {
                        if (!confirm('Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?')) {
                            e.preventDefault();
                        }
                    }
                });
            });
        }
    ">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Pengaturan Kuota Pembimbing</h2>
                <p class="text-base text-gray-500 dark:text-gray-400 mt-1">
                    Tahun Ajaran: <span class="font-medium text-school-blue">{{ $activeYear->name }}</span>
                </p>
            </div>

            <!-- Save Button -->
            <button type="button" @click="submitForm()" :disabled="!isDirty"
                class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-base font-medium text-white shadow-sm transition-all duration-200 ease-in-out hover:bg-school-blue/90 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none translate-y-0"
                :class="{ 'translate-y-1': !isDirty, 'shadow-lg ring-2 ring-school-blue/20': isDirty }">
                <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'scale-110': isDirty }" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>
                <span>Simpan Perubahan</span>
                <span x-show="isDirty" x-transition class="ml-1 text-xs bg-white/20 px-1.5 py-0.5 rounded-md">Unsaved</span>
            </button>
        </div>

        <div class="flex flex-col gap-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="flex w-full border-l-4 border-blue-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                    <div class="w-full flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Search & Filter Bar -->
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari berdasarkan nama atau NIP..."
                       class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue">
            </div>
            <select x-model="filterDepartment"
                    class="h-11 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer min-w-[180px]">
                <option value="all">Semua Jurusan</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            <select x-model="filterStatus"
                    class="h-11 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer min-w-[180px]">
                <option value="all">Semua Status</option>
                <option value="empty">Belum Diisi (Kuota 0)</option>
                <option value="filled">Sudah Diisi</option>
            </select>
        </div>

        <!-- Allocation Table Form -->
        <form x-ref="form" action="{{ route('supervisors.allocate.bulk') }}" method="POST" class="w-full">
            @csrf

            {{-- Hidden inputs for ALL supervisors so form submits complete data --}}
            @foreach ($supervisors as $supervisor)
                <input type="hidden" :name="'allocations[{{ $supervisor->user_id }}]'" x-model="allocations[{{ $supervisor->user_id }}]">
            @endforeach

            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-base text-gray-600 dark:text-gray-400">
                        <thead
                            class="bg-gray-50 text-sm uppercase text-gray-500 dark:bg-amoled-surface dark:text-gray-400 border-b border-gray-200 dark:border-amoled-border">
                            <tr>
                                <th class="px-6 py-4 font-semibold w-16">No</th>
                                <th class="px-6 py-4 font-semibold">Nama Guru Pembimbing</th>
                                <th class="px-6 py-4 font-semibold w-64">Jurusan</th>
                                <th class="px-6 py-4 font-semibold w-40 text-center">Kuota Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                            <template x-for="(supervisor, index) in filteredSupervisors" :key="supervisor.user_id">
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors duration-150">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200" x-text="index + 1"></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-9 w-9 rounded-full bg-school-blue/10 text-school-blue flex items-center justify-center text-xs font-bold flex-shrink-0"
                                                x-text="supervisor.name.substring(0, 2)">
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-medium text-gray-900 dark:text-white truncate" x-text="supervisor.name"></div>
                                                <div class="text-sm text-gray-400 truncate" x-text="supervisor.nip || '-'"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <template x-if="supervisor.department_name">
                                            <span
                                                class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-sm font-medium text-gray-600 dark:bg-white/[0.08] dark:text-gray-300"
                                                x-text="supervisor.department_name + ' (' + supervisor.department_code + ')'">
                                            </span>
                                        </template>
                                        <template x-if="!supervisor.department_name">
                                            <span class="text-gray-400 italic">-</span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="relative flex items-center justify-center"
                                            :class="{ 'text-school-blue font-bold': allocations[supervisor.user_id] != initialAllocations[supervisor.user_id] }">
                                            <input type="number"
                                                x-model.number="allocations[supervisor.user_id]"
                                                @input="checkDirty()" min="0"
                                                class="peer block w-24 rounded-lg border-0 bg-gray-50 py-2 px-3 text-center text-gray-900 ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-school-blue text-base sm:leading-6 dark:bg-white/[0.05] dark:text-white dark:ring-white/10 dark:focus:ring-school-blue transition-all duration-200
                                                          hover:bg-white dark:hover:bg-white/[0.08]"
                                                placeholder="0" />

                                            <!-- Indicator dot for changed value -->
                                            <div x-show="allocations[supervisor.user_id] != initialAllocations[supervisor.user_id]"
                                                x-transition
                                                class="absolute -right-2 top-1/2 -translate-y-1/2 h-1.5 w-1.5 rounded-full bg-amber-500">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="filteredSupervisors.length === 0">
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            <p class="text-base" x-text="supervisors.length === 0 ? 'Belum ada data guru pembimbing.' : 'Tidak ada guru yang cocok dengan filter.'"></p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Footer with summary -->
                <div
                    class="bg-gray-50 px-6 py-4 border-t border-gray-200 dark:bg-amoled-surface dark:border-amoled-border flex justify-between items-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Menampilkan <span class="font-medium" x-text="filteredSupervisors.length"></span> dari {{ count($supervisors) }} guru
                        pembimbing</span>
                    <div class="text-base font-medium text-gray-700 dark:text-gray-300">
                        Total Kuota: <span
                            x-text="Object.values(allocations).reduce((a, b) => parseInt(a||0) + parseInt(b||0), 0)"
                            class="font-bold text-school-blue">0</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
