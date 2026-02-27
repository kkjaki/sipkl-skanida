@extends('layouts.app')

@section('content')
@php
    $attendanceMap = [
        'present' => ['label' => 'Hadir', 'class' => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400'],
        'excused' => ['label' => 'Izin', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400'],
        'sick'    => ['label' => 'Sakit', 'class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400'],
    ];
    $statusMap = [
        'pending'  => ['label' => 'Pending', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400'],
        'verified' => ['label' => 'Tervalidasi', 'class' => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400'],
        'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400'],
    ];
@endphp

<div x-data="{
    selectedIds: [],
    showRejectModal: false,
    expandedRow: null,
    allIds: @js($journals->pluck('id')),

    toggleSelect(id) {
        const i = this.selectedIds.indexOf(id);
        i === -1 ? this.selectedIds.push(id) : this.selectedIds.splice(i, 1);
    },
    toggleAll() {
        this.selectedIds = this.selectedIds.length === this.allIds.length ? [] : [...this.allIds];
    },
    toggleExpand(id) {
        this.expandedRow = this.expandedRow === id ? null : id;
    },
    openRejectModal() {
        if (!this.selectedIds.length) return;
        this.showRejectModal = true;
    },
    closeRejectModal() {
        this.showRejectModal = false;
    }
}" class="space-y-4 sm:space-y-6">

    {{-- Back Link --}}
    <a href="{{ route('supervisor.journal-validations.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-school-blue transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    {{-- Student Info Card --}}
    <div class="bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-2xl p-4 sm:p-5">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-school-blue/10 flex items-center justify-center text-school-blue font-black text-base flex-shrink-0">
                {{ substr($internship->student->user->name, 0, 1) }}
            </div>
            <div class="min-w-0">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $internship->student->user->name }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $internship->student->class_name }} &bull; {{ $internship->student->nis }} &bull; {{ $internship->industry->name ?? '-' }}</p>
            </div>
        </div>
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

    {{-- Toolbar --}}
    <div class="bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-2xl p-3 sm:p-4">
        {{-- Desktop: filters left, actions right --}}
        <div class="hidden sm:flex items-center justify-between gap-4">
            <form method="GET" action="{{ route('supervisor.journal-validations.show', $internship) }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()"
                        class="h-9 min-w-[10rem] rounded-lg border border-gray-200 bg-gray-50 px-3 pr-8 text-sm text-gray-700 outline-none focus:border-school-blue focus:ring-2 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-white/[0.03] dark:text-white/90 appearance-none cursor-pointer">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Tervalidasi</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <select name="sort" onchange="this.form.submit()"
                        class="h-9 min-w-[8rem] rounded-lg border border-gray-200 bg-gray-50 px-3 pr-8 text-sm text-gray-700 outline-none focus:border-school-blue focus:ring-2 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-white/[0.03] dark:text-white/90 appearance-none cursor-pointer">
                    <option value="date_desc" {{ $sort === 'date_desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="date_asc" {{ $sort === 'date_asc' ? 'selected' : '' }}>Terlama</option>
                </select>
            </form>

            <div class="flex items-center gap-2">
                <form action="{{ route('supervisor.journal-validations.bulkUpdate', $internship) }}" method="POST"
                      onsubmit="return confirm('Validasi semua jurnal yang dipilih?')">
                    @csrf
                    <input type="hidden" name="action" value="verify">
                    <template x-for="id in selectedIds" :key="'v-'+id">
                        <input type="hidden" name="journal_ids[]" :value="id">
                    </template>
                    <button type="submit" :disabled="selectedIds.length === 0"
                            class="h-9 w-28 rounded-lg bg-green-600 hover:bg-green-700 text-white font-bold text-xs transition-colors flex items-center justify-center gap-1.5 disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Validasi
                        <span x-show="selectedIds.length" x-text="'(' + selectedIds.length + ')'" class="opacity-80"></span>
                    </button>
                </form>
                <button type="button" @click="openRejectModal()" :disabled="selectedIds.length === 0"
                        class="h-9 w-28 rounded-lg bg-red-500 hover:bg-red-600 text-white font-bold text-xs transition-colors flex items-center justify-center gap-1.5 disabled:opacity-30 disabled:cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak
                    <span x-show="selectedIds.length" x-text="'(' + selectedIds.length + ')'" class="opacity-80"></span>
                </button>
            </div>
        </div>

        {{-- Mobile: stacked --}}
        <div class="sm:hidden flex flex-col gap-2">
            <form method="GET" action="{{ route('supervisor.journal-validations.show', $internship) }}" class="grid grid-cols-2 gap-2">
                <select name="status" onchange="this.form.submit()"
                        class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none focus:border-school-blue dark:border-amoled-border dark:bg-white/[0.03] dark:text-white/90 appearance-none cursor-pointer">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Tervalidasi</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <select name="sort" onchange="this.form.submit()"
                        class="h-10 rounded-lg border border-gray-200 bg-gray-50 px-3 text-sm text-gray-700 outline-none focus:border-school-blue dark:border-amoled-border dark:bg-white/[0.03] dark:text-white/90 appearance-none cursor-pointer">
                    <option value="date_desc" {{ $sort === 'date_desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="date_asc" {{ $sort === 'date_asc' ? 'selected' : '' }}>Terlama</option>
                </select>
            </form>
            <div class="grid grid-cols-2 gap-2">
                <form action="{{ route('supervisor.journal-validations.bulkUpdate', $internship) }}" method="POST"
                      onsubmit="return confirm('Validasi semua jurnal yang dipilih?')">
                    @csrf
                    <input type="hidden" name="action" value="verify">
                    <template x-for="id in selectedIds" :key="'mv-'+id">
                        <input type="hidden" name="journal_ids[]" :value="id">
                    </template>
                    <button type="submit" :disabled="selectedIds.length === 0"
                            class="w-full h-10 rounded-lg bg-green-600 hover:bg-green-700 text-white font-bold text-sm transition-colors flex items-center justify-center gap-1.5 disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Validasi
                        <span x-show="selectedIds.length" x-text="'(' + selectedIds.length + ')'" class="text-xs opacity-80"></span>
                    </button>
                </form>
                <button type="button" @click="openRejectModal()" :disabled="selectedIds.length === 0"
                        class="w-full h-10 rounded-lg bg-red-500 hover:bg-red-600 text-white font-bold text-sm transition-colors flex items-center justify-center gap-1.5 disabled:opacity-30 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak
                    <span x-show="selectedIds.length" x-text="'(' + selectedIds.length + ')'" class="text-xs opacity-80"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Journal Table (Desktop) / Card List (Mobile) --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface overflow-hidden">

        {{-- Desktop Table --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-amoled-surface dark:text-gray-400 border-b border-gray-200 dark:border-amoled-border">
                    <tr>
                        <th class="px-4 py-3 w-10">
                            <div @click="toggleAll()"
                                 class="w-[18px] h-[18px] rounded border-2 flex items-center justify-center cursor-pointer transition-colors"
                                 :class="selectedIds.length === allIds.length && allIds.length > 0 ? 'bg-school-blue border-school-blue' : 'border-gray-300 dark:border-amoled-border hover:border-school-blue/50'">
                                <svg x-show="selectedIds.length === allIds.length && allIds.length > 0" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </th>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        <th class="px-4 py-3 font-semibold">Kehadiran</th>
                        <th class="px-4 py-3 font-semibold">Kegiatan</th>
                        <th class="px-4 py-3 font-semibold">Lampiran</th>
                        <th class="px-4 py-3 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                    @forelse($journals as $journal)
                        @php $att = $attendanceMap[$journal->status_attendance] ?? ['label'=>'-','class'=>'']; @endphp
                        @php $st = $statusMap[$journal->verification_status] ?? ['label'=>'-','class'=>'']; @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors cursor-pointer"
                            @click="toggleExpand({{ $journal->id }})">
                            <td class="px-4 py-3" @click.stop>
                                <div @click="toggleSelect({{ $journal->id }})"
                                     class="w-[18px] h-[18px] rounded border-2 flex items-center justify-center cursor-pointer transition-colors"
                                     :class="selectedIds.includes({{ $journal->id }}) ? 'bg-school-blue border-school-blue' : 'border-gray-300 dark:border-amoled-border hover:border-school-blue/50'">
                                    <svg x-show="selectedIds.includes({{ $journal->id }})" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $journal->date->format('d M Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $att['class'] }}">{{ $att['label'] }}</span>
                            </td>
                            <td class="px-4 py-3 max-w-[250px]">
                                <span class="text-gray-700 dark:text-gray-300 truncate block">{{ Str::limit($journal->activity, 50) ?: '-' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs">
                                @if($journal->attachment_path)
                                    <span class="text-school-blue font-medium">Ada</span>
                                @else
                                    <span class="text-gray-400 italic">Kosong</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $st['class'] }}">{{ $st['label'] }}</span>
                            </td>
                        </tr>
                        {{-- Expanded Content --}}
                        <tr x-show="expandedRow === {{ $journal->id }}" x-cloak x-transition.opacity.duration.150ms>
                            <td colspan="6" class="p-0">
                                <div class="px-5 py-4 bg-gray-50/80 dark:bg-white/[0.015] border-t border-gray-100 dark:border-amoled-border space-y-3">
                                    @include('journal-validations._expanded', ['journal' => $journal])
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 text-sm">Belum ada jurnal harian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="sm:hidden divide-y divide-gray-100 dark:divide-amoled-border">
            {{-- Select All --}}
            @if($journals->count() > 0)
                <div class="px-4 py-2.5 flex items-center gap-3 bg-gray-50/50 dark:bg-white/[0.01]" @click="toggleAll()">
                    <div class="w-[18px] h-[18px] rounded border-2 flex items-center justify-center cursor-pointer transition-colors flex-shrink-0"
                         :class="selectedIds.length === allIds.length && allIds.length > 0 ? 'bg-school-blue border-school-blue' : 'border-gray-300 dark:border-amoled-border'">
                        <svg x-show="selectedIds.length === allIds.length && allIds.length > 0" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400"
                          x-text="selectedIds.length === allIds.length && allIds.length > 0 ? 'Batalkan Semua' : 'Pilih Semua'">Pilih Semua</span>
                </div>
            @endif
            @forelse($journals as $journal)
                @php $att = $attendanceMap[$journal->status_attendance] ?? ['label'=>'-','class'=>'']; @endphp
                @php $st = $statusMap[$journal->verification_status] ?? ['label'=>'-','class'=>'']; @endphp
                <div class="px-4 py-3">
                    <div class="flex items-start gap-3" @click="toggleExpand({{ $journal->id }})">
                        {{-- Checkbox --}}
                        <div @click.stop="toggleSelect({{ $journal->id }})"
                             class="w-[18px] h-[18px] mt-0.5 rounded border-2 flex items-center justify-center cursor-pointer transition-colors flex-shrink-0"
                             :class="selectedIds.includes({{ $journal->id }}) ? 'bg-school-blue border-school-blue' : 'border-gray-300 dark:border-amoled-border'">
                            <svg x-show="selectedIds.includes({{ $journal->id }})" class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $journal->date->format('d M Y') }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $st['class'] }} flex-shrink-0">{{ $st['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $att['class'] }}">{{ $att['label'] }}</span>
                                @if($journal->attachment_path)
                                    <span class="text-[10px] font-medium text-school-blue">📎 Ada</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-1">{{ Str::limit($journal->activity, 60) ?: '-' }}</p>
                        </div>

                        {{-- Expand Arrow --}}
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-1 transition-transform"
                             :class="expandedRow === {{ $journal->id }} ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    {{-- Expanded Mobile --}}
                    <div x-show="expandedRow === {{ $journal->id }}" x-cloak x-transition.opacity.duration.150ms
                         class="mt-3 ml-[30px] space-y-3">
                        @include('journal-validations._expanded', ['journal' => $journal])
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 text-sm">Belum ada jurnal harian.</div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($journals->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-amoled-surface">
                {{ $journals->links() }}
            </div>
        @endif
    </div>

    {{-- Reject Modal --}}
    <div x-show="showRejectModal" class="fixed inset-0 z-[100]"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-black/70" @click="closeRejectModal()"></div>

            <div class="relative w-full sm:max-w-md bg-white dark:bg-amoled-surface rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-amoled-border"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="translate-y-full sm:translate-y-4 sm:scale-95 opacity-0"
                 x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100">
                <form action="{{ route('supervisor.journal-validations.bulkUpdate', $internship) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <template x-for="id in selectedIds" :key="'r-'+id">
                        <input type="hidden" name="journal_ids[]" :value="id">
                    </template>

                    <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-amoled-border flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">Tolak Jurnal</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><span x-text="selectedIds.length"></span> jurnal akan ditolak</p>
                        </div>
                        <button type="button" @click="closeRejectModal()" class="p-1.5 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/20 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-4 sm:p-5">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="rejection_note" rows="3" required
                                  placeholder="Tuliskan alasan mengapa jurnal ditolak..."
                                  class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-3 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-colors resize-none"></textarea>
                    </div>

                    <div class="p-4 sm:p-5 border-t border-gray-100 dark:border-amoled-border bg-gray-50/50 dark:bg-white/[0.01] grid grid-cols-2 gap-2">
                        <button type="button" @click="closeRejectModal()"
                                class="h-10 rounded-xl bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-300 dark:hover:bg-white/20 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="h-10 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-sm transition-colors">
                            Tolak Jurnal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
