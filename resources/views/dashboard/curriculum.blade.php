@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Page Header -->
        <div class="min-h-[44px]">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Dashboard Kurikulum
            </h2>
            <p class="text-sm text-gray-500 dark:text-amoled-text mt-1">
                @if($activeYear)
                    Tahun Ajaran: <span class="font-semibold text-school-blue">{{ $activeYear->name }}</span>
                @endif
            </p>
        </div>

        @if(!$activeYear)
            <div class="flex w-full border-l-4 border-amber-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    <p class="text-sm text-amber-600 dark:text-amber-400 font-medium">
                        Belum ada tahun ajaran aktif. Hubungi Admin untuk mengaktifkan tahun ajaran.
                    </p>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Card 1: Total Guru Pembimbing (Blue/Violet) -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface transition hover:border-violet-300 dark:hover:border-violet-500/40 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-medium text-gray-500 dark:text-amoled-text">Total Guru Pembimbing Teralokasi</p>
                        <h3 class="mt-2 text-3xl font-bold text-violet-600 dark:text-violet-400">
                            {{ $totalSupervisorsAllocated }} dari {{ $totalSupervisors }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">guru pembimbing siap membimbing siswa</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/10 dark:bg-violet-500/20">
                        <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Kuota (Emerald) -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface transition hover:border-emerald-300 dark:hover:border-emerald-500/40 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-medium text-gray-500 dark:text-amoled-text">Persentase Kuota Guru Pembimbing</p>
                        <h3 class="mt-2 text-3xl font-bold text-emerald-500">
                            {{ round(($totalAllocatedQuota / $totalStudents) * 100, 1) }}%
                        </h3>
                        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">kuota tersedia dibanding jumlah siswa</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20">
                        <svg class="w-6 h-6 text-emerald-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Indikator Penilaian (Amber) -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface transition hover:border-amber-300 dark:hover:border-amber-500/40 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-medium text-gray-500 dark:text-amoled-text">Indikator Penilaian</p>
                        <h3 class="mt-2 text-3xl font-bold text-amber-500">
                            {{ $totalIndicators }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">aspek evaluasi aktif</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/10 dark:bg-amber-500/20">
                        <svg class="w-6 h-6 text-amber-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
