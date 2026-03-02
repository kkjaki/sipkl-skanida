@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        {{-- Page Header --}}
        <div class="min-h-[44px]">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-amoled-text mt-1">
                Selamat datang kembali, <span class="font-semibold text-school-blue">{{ $user->name }}</span>
            </p>
        </div>

        {{-- PKL Status Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                <h2 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Status PKL Saya</h2>
            </div>
            <div class="p-6 sm:p-8">
                @if(!$student)
                    {{-- No student data --}}
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-500/10 dark:bg-red-500/20 mb-4">
                            <svg class="w-7 h-7 text-red-500" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">Data siswa tidak ditemukan</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Hubungi admin untuk memastikan data Anda sudah terdaftar di sistem.</p>
                    </div>

                @elseif($internship)
                    {{-- Sudah ditempatkan --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 shrink-0">
                            <svg class="w-7 h-7 text-emerald-500" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">Sudah Ditempatkan</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $internship->industry->name ?? '-' }}
                                @if($internship->industry?->city)
                                    <span class="text-gray-400 dark:text-gray-500">— {{ $internship->industry->city }}</span>
                                @endif
                            </p>
                            @if($internship->start_date)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    Mulai: {{ $internship->start_date->format('d M Y') }}
                                </p>
                            @endif
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-3 py-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif PKL
                        </span>
                    </div>

                @else
                    {{-- Belum ditempatkan --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 shrink-0">
                            <svg class="w-7 h-7 text-amber-500" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">Belum Ditempatkan</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Kamu belum ditempatkan di lokasi PKL. Silakan ajukan lokasi PKL atau tunggu penempatan dari Kaprog.
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/10 px-3 py-1.5 text-xs font-semibold text-amber-600 dark:text-amber-400 whitespace-nowrap">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Menunggu
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Journal Stats (only if placed) --}}
        @if($internship)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                {{-- Total Journals --}}
                <a href="{{ route('student.journals.index') }}" class="block group">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface transition hover:border-school-blue/40 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Total Jurnal</p>
                                <h3 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">{{ $totalJournals }}</h3>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">jurnal disubmit</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-school-blue/10 dark:bg-school-blue/20 shrink-0">
                                <svg class="w-6 h-6 text-school-blue" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Verified Journals --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Diverifikasi</p>
                            <h3 class="mt-2 text-3xl font-bold text-emerald-500">{{ $verifiedJournals }}</h3>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">jurnal disetujui</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 shrink-0">
                            <svg class="w-6 h-6 text-emerald-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pending Journals --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Menunggu Verifikasi</p>
                            <h3 class="mt-2 text-3xl font-bold {{ $pendingJournals > 0 ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500' }}">{{ $pendingJournals }}</h3>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">jurnal pending</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $pendingJournals > 0 ? 'bg-amber-500/10 dark:bg-amber-500/20' : 'bg-gray-100 dark:bg-white/[0.06]' }} shrink-0">
                            <svg class="w-6 h-6 {{ $pendingJournals > 0 ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500' }}" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Quick Actions --}}
        @if($student)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h2 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Aksi Cepat</h2>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if(!$internship)
                            <a href="{{ route('student.proposals.index') }}" class="flex items-center gap-4 rounded-xl border border-gray-100 dark:border-amoled-border p-4 transition hover:bg-gray-50 dark:hover:bg-white/[0.03] group">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-school-blue/10 dark:bg-school-blue/20 shrink-0">
                                    <svg class="w-5 h-5 text-school-blue" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-school-blue transition">Ajukan Lokasi PKL</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Usulkan perusahaan baru untuk lokasi PKL</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-school-blue transition shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                        @if($internship)
                            <a href="{{ route('student.journals.index') }}" class="flex items-center gap-4 rounded-xl border border-gray-100 dark:border-amoled-border p-4 transition hover:bg-gray-50 dark:hover:bg-white/[0.03] group">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/10 dark:bg-emerald-500/20 shrink-0">
                                    <svg class="w-5 h-5 text-emerald-500" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-emerald-500 transition">Isi Jurnal Harian</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Catat kegiatan PKL harian Anda</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-emerald-500 transition shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
