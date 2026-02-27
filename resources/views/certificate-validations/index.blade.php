@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6" x-data="{ showErrorModal: false }">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Validasi Data Sertifikat
            </h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li>
                        <a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Validasi Sertifikat</li>
                </ol>
            </nav>
        </div>

        @if(!$internship)
            {{-- STATE 1: No Active Internship --}}
            <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-2xl">
                <div class="p-4 rounded-full bg-yellow-50 dark:bg-yellow-500/10 text-yellow-500 mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada PKL Aktif</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center mt-1">Anda belum memiliki penempatan PKL yang aktif. Hubungi Kaprog untuk informasi lebih lanjut.</p>
            </div>

        @elseif(!$hasScores)
            {{-- STATE 2: Internship exists but no scores yet --}}
            <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-2xl">
                <div class="p-4 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-500 mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sertifikat Belum Tersedia</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm text-center mt-1">Selesaikan magang dan pastikan nilai telah diinput oleh Guru Pembimbing sebelum melakukan validasi data sertifikat.</p>
            </div>

        @else
            {{-- STATE 3: Ready — show certificate validation flow --}}

            {{-- Warning Banner --}}
            <div class="rounded-2xl border border-amber-500/30 bg-amber-500/5 p-4 sm:p-5">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p class="text-sm text-amber-700 dark:text-amber-400 leading-relaxed">
                        Mohon periksa kembali ejaan <strong>seluruh data diri</strong> Anda sebelum sertifikat dicetak.
                        <span class="font-semibold">Kesalahan data setelah proses cetak bukan tanggung jawab sekolah.</span>
                    </p>
                </div>
            </div>

            {{-- Status Banner --}}
            @if($certificate->status === 'validated')
                <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/5 p-4 sm:p-5">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                            Data telah divalidasi. Menunggu proses cetak oleh Admin/Humas.
                        </p>
                    </div>
                </div>
            @elseif($certificate->status === 'generated')
                <div class="rounded-2xl border border-indigo-500/30 bg-indigo-500/5 p-4 sm:p-5">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-400">
                            Sertifikat Anda telah selesai dicetak.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Data Preview Card --}}
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-5 sm:px-8">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Pratinjau Data Sertifikat</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Data di bawah ini akan tercetak pada sertifikat Anda.</p>
                </div>

                <div class="p-5 sm:p-8">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                        {{-- Nama --}}
                        <div>
                            <dt class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-white">{{ $internship->student->user->name ?? '-' }}</dd>
                        </div>

                        {{-- NIS --}}
                        <div>
                            <dt class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">NIS</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-white font-mono">{{ $internship->student->nis ?? '-' }}</dd>
                        </div>

                        {{-- TTL --}}
                        <div>
                            <dt class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Tempat, Tanggal Lahir</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-white">
                                {{ $internship->student->place_of_birth }}, {{ \Carbon\Carbon::parse($internship->student->date_of_birth)->locale('id')->translatedFormat('d F Y') }}
                            </dd>
                        </div>

                        {{-- Kelas --}}
                        <div>
                            <dt class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Kelas</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-white">{{ $internship->student->class_name ?? '-' }}</dd>
                        </div>

                        {{-- Tempat PKL --}}
                        <div>
                            <dt class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Tempat PKL</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-white">{{ $internship->industry->name ?? '-' }}</dd>
                        </div>

                        {{-- PIC Industri --}}
                        <div>
                            <dt class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Penanggung Jawab (PIC)</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-white">{{ $internship->industry->pic_name ?? '-' }}</dd>
                        </div>

                        {{-- Status --}}
                        <div>
                            <dt class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1.5">Status Sertifikat</dt>
                            <dd>
                                @if($certificate->status === 'draft')
                                    <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-amber-500/10 text-amber-600 border-amber-500/20">
                                        Menunggu Validasi
                                    </span>
                                @elseif($certificate->status === 'validated')
                                    <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20">
                                        Sudah Divalidasi
                                    </span>
                                @elseif($certificate->status === 'generated')
                                    <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-indigo-500/10 text-indigo-600 border-indigo-500/20">
                                        Sudah Dicetak
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Action Buttons — only show for 'draft' status --}}
            @if($certificate->status === 'draft')
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Validate Button --}}
                    <form action="{{ route('student.certificate-validations.validate', $certificate) }}" method="POST" class="flex-1 sm:flex-initial">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 py-3 px-6 text-sm font-semibold text-white hover:bg-emerald-700 transition duration-150 shadow-sm"
                                onclick="return confirm('Apakah Anda yakin data di atas sudah benar? Tindakan ini tidak dapat dibatalkan.')"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Saya nyatakan data di atas SUDAH BENAR
                        </button>
                    </form>

                    {{-- Report Error Button --}}
                    <button
                        type="button"
                        @click="showErrorModal = true"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-500/30 py-3 px-6 text-sm font-semibold text-red-500 hover:bg-red-500/5 transition duration-150"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Laporkan Kesalahan Data
                    </button>
                </div>
            @endif

            {{-- Error Report Modal --}}
            <div
                x-show="showErrorModal"
                x-cloak
                x-transition.opacity.duration.200ms
                class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-sm"
                @click.self="showErrorModal = false"
                @keydown.escape.window="showErrorModal = false"
            >
                <div
                    x-show="showErrorModal"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="w-full max-w-md rounded-2xl bg-white dark:bg-amoled-surface shadow-xl border border-gray-200 dark:border-amoled-border p-6"
                >
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-500/10">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Laporkan Kesalahan</h3>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                        Silakan hubungi bagian <strong class="text-gray-800 dark:text-white">Admin/Humas</strong> untuk memperbaiki data Anda pada sistem. Pastikan untuk menyampaikan data mana yang perlu diperbaiki.
                    </p>

                    <button
                        @click="showErrorModal = false"
                        type="button"
                        class="w-full rounded-xl bg-gray-100 dark:bg-white/[0.08] py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/[0.12] transition duration-150"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        @endif
    </div>
@endsection
