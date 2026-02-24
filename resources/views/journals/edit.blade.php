@extends('layouts.app')

@section('content')
<div x-data="{
    attendance: '{{ old('status_attendance', $journal->status_attendance) }}',
    fileName: '',
    handleFileChange(e) {
        this.fileName = e.target.files[0] ? e.target.files[0].name : '';
    }
}" class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-1">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.journals.index') }}"
               class="p-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $journal->verification_status === 'rejected' ? 'Revisi Jurnal' : 'Edit Jurnal' }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Jurnal tanggal <span class="font-medium text-school-blue">{{ $journal->date->translatedFormat('d F Y') }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Rejection Warning Banner --}}
    @if($journal->verification_status === 'rejected' && $journal->rejection_note)
        <div class="p-4 sm:p-5 rounded-2xl bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20">
            <div class="flex items-start gap-3">
                <div class="p-2 rounded-xl bg-red-100 dark:bg-red-500/10 text-red-500 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-700 dark:text-red-400">Jurnal Ditolak</p>
                    <p class="text-sm text-red-600 dark:text-red-300 mt-1 leading-relaxed">{{ $journal->rejection_note }}</p>
                    <p class="text-xs text-red-500/70 dark:text-red-400/50 mt-2">Perbaiki jurnal sesuai catatan di atas, lalu kirim ulang.</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-4 text-sm text-red-800 border border-red-300 rounded-xl bg-red-50 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800/50">
            <p class="font-bold mb-1">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit Form --}}
    <div class="bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-3xl overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-gray-100 dark:border-amoled-border">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-school-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ $journal->verification_status === 'rejected' ? 'Perbaiki Jurnal' : 'Ubah Data Jurnal' }}
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Setelah disimpan, status validasi akan kembali ke <span class="font-semibold text-yellow-600 dark:text-yellow-500">Pending</span>.</p>
        </div>

        <form action="{{ route('student.journals.update', $journal) }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Date --}}
            <div>
                <label for="date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5">Tanggal</label>
                <input type="date" id="date" name="date" value="{{ old('date', $journal->date->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}"
                       class="h-11 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:focus:border-school-blue">
                @error('date')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Attendance Status --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2.5">Status Kehadiran</label>
                <div class="grid grid-cols-3 gap-3">
                    {{-- Hadir --}}
                    <label class="relative cursor-pointer" @click="attendance = 'present'">
                        <input type="radio" name="status_attendance" value="present" x-model="attendance" class="sr-only peer">
                        <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all duration-200
                                    peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:ring-2 peer-checked:ring-green-500/20
                                    border-gray-200 dark:border-amoled-border hover:border-green-300 dark:hover:border-green-500/30">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                                 :class="attendance === 'present' ? 'bg-green-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-400'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider" :class="attendance === 'present' ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'">Hadir</span>
                        </div>
                    </label>

                    {{-- Sakit --}}
                    <label class="relative cursor-pointer" @click="attendance = 'sick'">
                        <input type="radio" name="status_attendance" value="sick" x-model="attendance" class="sr-only peer">
                        <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all duration-200
                                    peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10 peer-checked:ring-2 peer-checked:ring-red-500/20
                                    border-gray-200 dark:border-amoled-border hover:border-red-300 dark:hover:border-red-500/30">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                                 :class="attendance === 'sick' ? 'bg-red-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-400'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider" :class="attendance === 'sick' ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400'">Sakit</span>
                        </div>
                    </label>

                    {{-- Izin --}}
                    <label class="relative cursor-pointer" @click="attendance = 'excused'">
                        <input type="radio" name="status_attendance" value="excused" x-model="attendance" class="sr-only peer">
                        <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all duration-200
                                    peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-500/10 peer-checked:ring-2 peer-checked:ring-yellow-500/20
                                    border-gray-200 dark:border-amoled-border hover:border-yellow-300 dark:hover:border-yellow-500/30">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                                 :class="attendance === 'excused' ? 'bg-yellow-500 text-white' : 'bg-gray-100 dark:bg-white/5 text-gray-400'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider" :class="attendance === 'excused' ? 'text-yellow-600 dark:text-yellow-500' : 'text-gray-500 dark:text-gray-400'">Izin</span>
                        </div>
                    </label>
                </div>
                @error('status_attendance')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Activity (shown only when 'present') --}}
            <div x-show="attendance === 'present'" x-cloak>
                <label for="activity" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1.5">Kegiatan Hari Ini <span class="text-red-500">*</span></label>
                <textarea id="activity" name="activity" rows="4" placeholder="Jelaskan kegiatan yang dilakukan hari ini..."
                          class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue resize-none">{{ old('activity', $journal->activity) }}</textarea>
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

                {{-- Current File Info --}}
                @if($journal->attachment_path)
                    <div class="mb-2 p-3 rounded-xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-amoled-border flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 min-w-0">
                            <svg class="w-4 h-4 flex-shrink-0 text-school-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <span class="truncate">File saat ini terlampir</span>
                        </div>
                        <a href="{{ Storage::url($journal->attachment_path) }}" target="_blank" class="text-xs font-bold text-school-blue hover:underline flex-shrink-0">Lihat</a>
                    </div>
                @endif

                <label class="flex flex-col items-center justify-center w-full h-32 rounded-2xl border-2 border-dashed transition-all duration-200 cursor-pointer
                              border-gray-200 dark:border-amoled-border hover:border-school-blue/50 dark:hover:border-school-blue/30 bg-gray-50 dark:bg-amoled-input">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!fileName">
                        <svg class="w-8 h-8 mb-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><span class="font-semibold text-school-blue">Klik untuk ganti file</span></p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">JPG, PNG, atau PDF (Maks. 2MB)</p>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-school-blue font-medium" x-show="fileName" x-cloak>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-text="fileName"></span>
                    </div>
                    <input type="file" name="attachment_path" class="hidden" accept=".jpg,.jpeg,.png,.pdf" @change="handleFileChange($event)">
                </label>
                @error('attachment_path')
                    <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="pt-2 flex items-center gap-3">
                <button type="submit"
                        class="py-3 px-8 rounded-2xl bg-school-blue hover:bg-school-blue/90 text-white font-bold text-sm transition-all shadow-lg shadow-school-blue/10 flex items-center justify-center gap-2 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $journal->verification_status === 'rejected' ? 'Kirim Revisi' : 'Simpan Perubahan' }}
                </button>
                <a href="{{ route('student.journals.index') }}"
                   class="py-3 px-6 rounded-2xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 font-bold text-sm transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
