@extends('layouts.app')

@section('content')
<div x-data="{
    activeTab: '{{ $errors->any() ? 'form' : (session('success') ? 'history' : 'form') }}',
    attendance: '{{ old('status_attendance', 'present') }}',
    fileName: '',
    handleFileChange(e) {
        this.fileName = e.target.files[0] ? e.target.files[0].name : '';
    }
}" class="space-y-4 sm:space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
        <div class="flex flex-col gap-1">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Jurnal Harian PKL</h1>
            @if($internship)
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tempat PKL: <span class="font-medium text-school-blue">{{ $internship->industry->name ?? '-' }}</span>
                </p>
            @endif
        </div>
        <nav>
            <ol class="flex items-center gap-1.5 text-sm">
                <li>
                    <a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="text-gray-300 dark:text-gray-600">/</li>
                <li class="font-medium text-gray-800 dark:text-gray-200">Jurnal Harian</li>
            </ol>
        </nav>
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

    @if(!$internship)
        {{-- No Active Internship --}}
        <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-2xl">
            <div class="p-4 rounded-full bg-yellow-50 dark:bg-yellow-500/10 text-yellow-500 mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada PKL Aktif</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center mt-1">Anda belum memiliki penempatan PKL yang aktif. Hubungi Kaprog untuk informasi lebih lanjut.</p>
        </div>
    @else
        {{-- Tab Navigation --}}
        <div class="flex gap-1 bg-gray-100 dark:bg-white/[0.04] p-1 rounded-xl w-fit">
            <button @click="activeTab = 'form'" type="button"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-150"
                    :class="activeTab === 'form'
                        ? 'bg-white dark:bg-amoled-surface text-gray-900 dark:text-white shadow-sm'
                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Isi Jurnal
                </span>
            </button>
            <button @click="activeTab = 'history'" type="button"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-150"
                    :class="activeTab === 'history'
                        ? 'bg-white dark:bg-amoled-surface text-gray-900 dark:text-white shadow-sm'
                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat
                    @if($journals->total() > 0)
                        <span class="ml-0.5 px-1.5 py-0.5 rounded-md bg-gray-200 dark:bg-white/10 text-[10px] font-bold text-gray-600 dark:text-gray-400">{{ $journals->total() }}</span>
                    @endif
                </span>
            </button>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB 1: FORM JURNAL --}}
        {{-- ============================================================ --}}
        <div x-show="activeTab === 'form'" x-cloak>
            <div class="bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-amoled-border">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Isi Jurnal Hari Ini</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Lengkapi presensi dan kegiatan harian Anda.</p>
                </div>

                <form action="{{ route('student.journals.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-5 space-y-5">
                    @csrf

                    {{-- Date + Attendance side by side on desktop --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Date --}}
                        <div>
                            <label for="date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5">Tanggal</label>
                            <input type="date" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}"
                                   class="h-11 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:focus:border-school-blue">
                            @error('date')
                                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Attendance Status --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5">Status Kehadiran</label>
                            <div class="grid grid-cols-3 gap-2">
                                {{-- Hadir --}}
                                <label class="relative cursor-pointer" @click="attendance = 'present'">
                                    <input type="radio" name="status_attendance" value="present" x-model="attendance" class="sr-only peer">
                                    <div class="flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border-2 transition-all duration-150
                                                peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10
                                                border-gray-200 dark:border-amoled-border hover:border-green-300 dark:hover:border-green-500/30">
                                        <div class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors"
                                             :class="attendance === 'present' ? 'bg-green-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-400'">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span class="text-xs font-bold" :class="attendance === 'present' ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'">Hadir</span>
                                    </div>
                                </label>

                                {{-- Sakit --}}
                                <label class="relative cursor-pointer" @click="attendance = 'sick'">
                                    <input type="radio" name="status_attendance" value="sick" x-model="attendance" class="sr-only peer">
                                    <div class="flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border-2 transition-all duration-150
                                                peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10
                                                border-gray-200 dark:border-amoled-border hover:border-red-300 dark:hover:border-red-500/30">
                                        <div class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors"
                                             :class="attendance === 'sick' ? 'bg-red-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-400'">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        </div>
                                        <span class="text-xs font-bold" :class="attendance === 'sick' ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400'">Sakit</span>
                                    </div>
                                </label>

                                {{-- Izin --}}
                                <label class="relative cursor-pointer" @click="attendance = 'excused'">
                                    <input type="radio" name="status_attendance" value="excused" x-model="attendance" class="sr-only peer">
                                    <div class="flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border-2 transition-all duration-150
                                                peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-500/10
                                                border-gray-200 dark:border-amoled-border hover:border-yellow-300 dark:hover:border-yellow-500/30">
                                        <div class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors"
                                             :class="attendance === 'excused' ? 'bg-yellow-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-400'">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <span class="text-xs font-bold" :class="attendance === 'excused' ? 'text-yellow-600 dark:text-yellow-500' : 'text-gray-500 dark:text-gray-400'">Izin</span>
                                    </div>
                                </label>
                            </div>
                            @error('status_attendance')
                                <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Activity (shown only when present) --}}
                    <div x-show="attendance === 'present'" x-cloak>
                        <label for="activity" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5">Kegiatan Hari Ini <span class="text-red-500">*</span></label>
                        <textarea id="activity" name="activity" rows="4" placeholder="Jelaskan kegiatan yang dilakukan hari ini..."
                                  class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue resize-none">{{ old('activity') }}</textarea>
                        @error('activity')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Attachment --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5">
                            <span x-show="attendance === 'present'" x-cloak>Lampiran Dokumentasi <span class="text-gray-400 font-normal">(Opsional)</span></span>
                            <span x-show="attendance !== 'present'" x-cloak>Upload Surat Keterangan / Bukti <span class="text-red-500">*</span></span>
                        </label>
                        <label class="flex flex-col items-center justify-center w-full h-28 rounded-xl border-2 border-dashed transition-all duration-150 cursor-pointer
                                      border-gray-200 dark:border-amoled-border hover:border-school-blue/50 dark:hover:border-school-blue/30 bg-gray-50 dark:bg-amoled-input">
                            <div class="flex flex-col items-center justify-center py-4" x-show="!fileName">
                                <svg class="w-7 h-7 mb-1.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><span class="font-semibold text-school-blue">Klik untuk upload</span> atau drag & drop</p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">JPG, PNG, atau PDF (Maks. 2MB)</p>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-school-blue font-medium" x-show="fileName" x-cloak>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="fileName"></span>
                            </div>
                            <input type="file" name="attachment_path" class="hidden" accept=".jpg,.jpeg,.png,.pdf" @change="handleFileChange($event)">
                        </label>
                        @error('attachment_path')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div>
                        <button type="submit"
                                class="w-full sm:w-auto py-2.5 px-6 rounded-xl bg-school-blue hover:bg-school-blue/90 text-white font-bold text-sm transition-all shadow-lg shadow-school-blue/10 flex items-center justify-center gap-2 active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Simpan Jurnal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- TAB 2: RIWAYAT JURNAL --}}
        {{-- ============================================================ --}}
        <div x-show="activeTab === 'history'" x-cloak>
            @if($journals->count() > 0)
                <div class="space-y-3">
                    @foreach($journals as $journal)
                        <div class="bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-2xl overflow-hidden transition-all duration-150 hover:shadow-md hover:shadow-gray-100/50 dark:hover:shadow-none">
                            <div class="p-4 sm:p-5">
                                {{-- Top Row: Date + Status Badge --}}
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-11 h-11 rounded-xl flex flex-col items-center justify-center flex-shrink-0
                                                    @if($journal->status_attendance === 'present') bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400
                                                    @elseif($journal->status_attendance === 'sick') bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400
                                                    @else bg-yellow-100 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-500
                                                    @endif">
                                            <span class="text-sm font-black leading-none">{{ $journal->date->format('d') }}</span>
                                            <span class="text-[9px] font-bold uppercase tracking-wider leading-none mt-0.5">{{ $journal->date->translatedFormat('M') }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $journal->date->translatedFormat('l, d F Y') }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                @if($journal->status_attendance === 'present')
                                                    <span class="inline-flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg> Hadir</span>
                                                @elseif($journal->status_attendance === 'sick')
                                                    <span class="inline-flex items-center gap-1 text-red-500"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> Sakit</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-yellow-500"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg> Izin</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Validation Badge --}}
                                    <div class="flex-shrink-0">
                                        @if($journal->verification_status === 'pending')
                                            <span class="px-2 py-0.5 rounded-lg bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-500 text-[10px] font-bold uppercase tracking-wider">Pending</span>
                                        @elseif($journal->verification_status === 'verified')
                                            <span class="px-2 py-0.5 rounded-lg bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-500 text-[10px] font-bold uppercase tracking-wider">Tervalidasi</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-lg bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-500 text-[10px] font-bold uppercase tracking-wider">Ditolak</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Activity --}}
                                @if($journal->activity)
                                    <div class="pl-14">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed line-clamp-3">{{ $journal->activity }}</p>
                                    </div>
                                @endif

                                {{-- Attachment --}}
                                @if($journal->attachment_path)
                                    <div class="pl-14 mt-2">
                                        <a href="{{ Storage::url($journal->attachment_path) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 text-xs font-medium text-school-blue hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            Lihat Lampiran
                                        </a>
                                    </div>
                                @endif

                                {{-- Rejection Note --}}
                                @if($journal->verification_status === 'rejected' && $journal->rejection_note)
                                    <div class="mt-3 ml-14 p-3 rounded-xl bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <div>
                                                <p class="text-[10px] font-bold text-red-600 dark:text-red-400 uppercase tracking-wider mb-0.5">Catatan Penolakan</p>
                                                <p class="text-xs text-red-700 dark:text-red-300 leading-relaxed">{{ $journal->rejection_note }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Action Buttons --}}
                                @if(in_array($journal->verification_status, ['pending', 'rejected']))
                                    <div class="mt-3 pl-14">
                                        <a href="{{ route('student.journals.edit', $journal) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors
                                                  @if($journal->verification_status === 'rejected')
                                                      bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-500/20
                                                  @else
                                                      bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10
                                                  @endif">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            {{ $journal->verification_status === 'rejected' ? 'Revisi' : 'Edit' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $journals->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-2xl">
                    <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Jurnal</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center mt-1">Mulai isi jurnal harian Anda melalui tab "Isi Jurnal".</p>
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
