@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Page Header -->
        <div class="min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Dashboard Kaprog
            </h2>
            <p class="text-sm text-gray-500 dark:text-amoled-text mt-1">
                {{ $department->name }} (<span class="font-semibold text-school-blue">{{ $department->code }}</span>)
                @if($activeYear)
                    — Tahun Ajaran: <span class="font-semibold text-school-blue">{{ $activeYear->name }}</span>
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
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Card 1: Menunggu Verifikasi (Amber) -->
            <a href="{{ route('verification.index') }}" class="block group">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface transition hover:border-amber-300 dark:hover:border-amber-500/40 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Menunggu Verifikasi</p>
                            <h3 class="mt-2 text-3xl font-bold text-amber-500">
                                {{ $waitingApproval }}
                            </h3>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">pengajuan siswa</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/10 dark:bg-amber-500/20">
                            <svg class="w-6 h-6 text-amber-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card 2: Siswa Belum PKL (Red) -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Siswa Belum Dapat Tempat</p>
                        <h3 class="mt-2 text-3xl font-bold text-red-500">
                            {{ $siswaBelumPkl }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">peserta didik</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-500/10 dark:bg-red-500/20">
                        <svg class="w-6 h-6 text-red-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3: Mitra Aktif (Coming Soon) -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface opacity-60">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Total Mitra {{ $department->code }}</p>
                        <h3 class="mt-2 text-lg font-bold text-gray-400 dark:text-gray-500">
                            Coming Soon
                        </h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">menunggu manajemen MoU</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-white/[0.06]">
                        <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3m2 0V5m12 0v16m-5-14h.01M9 7h.01M9 11h.01M12 11h.01M9 15h.01M12 15h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Proposals Table -->
        @if($recentProposals->count() > 0)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">
                        Pengajuan Terbaru dari Siswa
                    </h3>
                    <a href="{{ route('verification.index') }}" class="text-xs font-medium text-school-blue hover:underline">Lihat Semua →</a>
                </div>

                <!-- Desktop Table -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-amoled-border">
                                <th class="py-3 px-6 sm:px-8 font-medium text-gray-500 dark:text-amoled-text">Nama Industri</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-amoled-text">Diajukan Oleh</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-amoled-text">Tanggal</th>
                                <th class="py-3 px-6 sm:px-8 font-medium text-gray-500 dark:text-amoled-text text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                            @foreach($recentProposals as $proposal)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.03] transition duration-150">
                                    <td class="py-3.5 px-6 sm:px-8">
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $proposal->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $proposal->city }}</p>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300">
                                        {{ $proposal->studentSubmitter->name ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-500 dark:text-gray-400">
                                        {{ $proposal->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-3.5 px-6 sm:px-8 text-right">
                                        <a href="{{ route('verification.show', $proposal->id) }}"
                                           class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500/10 px-3 py-1.5 text-xs font-medium text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 transition duration-150">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            Cek & Sync
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="sm:hidden divide-y divide-gray-100 dark:divide-amoled-border">
                    @foreach($recentProposals as $proposal)
                        <div class="p-4 space-y-2">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white text-sm">{{ $proposal->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $proposal->city }} &bull; {{ $proposal->created_at->format('d M Y') }}</p>
                                </div>
                                <a href="{{ route('verification.show', $proposal->id) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-amber-500/10 px-2.5 py-1.5 text-xs font-medium text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 shrink-0">
                                    Cek & Sync
                                </a>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Diajukan oleh: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $proposal->studentSubmitter->name ?? '-' }}</span></p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface p-8 text-center">
                <svg class="mx-auto w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Tidak ada pengajuan yang menunggu verifikasi.</p>
            </div>
        @endif
    </div>
@endsection
