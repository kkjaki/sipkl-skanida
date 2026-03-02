@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        {{-- Page Header --}}
        <div class="min-h-[44px]">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
            @if($activeYear)
                <p class="text-sm text-gray-500 dark:text-amoled-text mt-1">
                    Tahun Ajaran Aktif: <span class="font-semibold text-school-blue">{{ $activeYear->name }}</span>
                </p>
            @endif
        </div>

        @if(!$activeYear)
            {{-- No Active Year Warning --}}
            <div class="flex w-full border-l-4 border-amber-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-sm text-amber-600 dark:text-amber-400 font-medium">
                        Belum ada tahun ajaran aktif. Silakan aktifkan tahun ajaran terlebih dahulu di menu <a href="{{ route('academic-years.index') }}" class="underline hover:text-amber-700">Tahun Ajaran</a>.
                    </p>
                </div>
            </div>
        @endif

        {{-- Row 1: Students & Year --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Total Students --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Total Peserta Didik</p>
                        <h3 class="mt-2 text-3xl font-bold text-gray-800 dark:text-white">
                            {{ number_format($stats['total_students']) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $stats['active_year'] ?? '-' }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-school-blue/10 dark:bg-school-blue/20 shrink-0">
                        <svg class="w-6 h-6 text-school-blue" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Synced Industries --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Industri Terverifikasi</p>
                        <h3 class="mt-2 text-3xl font-bold text-emerald-500">
                            {{ number_format($stats['total_synced_industries']) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">mitra siap digunakan</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 shrink-0">
                        <svg class="w-6 h-6 text-emerald-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3m2 0V5m12 0v16m-5-14h.01M9 7h.01M9 11h.01M12 11h.01M9 15h.01M12 15h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: Placement Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            {{-- Students Placed --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Sudah Ditempatkan</p>
                        <h3 class="mt-2 text-3xl font-bold text-emerald-500">
                            {{ number_format($stats['students_placed']) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">siswa sudah PKL</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 shrink-0">
                        <svg class="w-6 h-6 text-emerald-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Students Unplaced --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Belum Ditempatkan</p>
                        <h3 class="mt-2 text-3xl font-bold {{ $stats['students_unplaced'] > 0 ? 'text-red-500' : 'text-gray-400 dark:text-gray-500' }}">
                            {{ number_format($stats['students_unplaced']) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">siswa belum PKL</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $stats['students_unplaced'] > 0 ? 'bg-red-500/10 dark:bg-red-500/20' : 'bg-gray-100 dark:bg-white/[0.06]' }} shrink-0">
                        <svg class="w-6 h-6 {{ $stats['students_unplaced'] > 0 ? 'text-red-500' : 'text-gray-400 dark:text-gray-500' }}" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Active Supervisors --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Guru Pembimbing</p>
                        <h3 class="mt-2 text-3xl font-bold text-violet-600 dark:text-violet-400">
                            {{ number_format($stats['active_supervisors']) }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">pembimbing aktif</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/10 dark:bg-violet-500/20 shrink-0">
                        <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Students per Department --}}
        @if($stats['students_per_dept']->count() > 0)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">
                        Peserta Didik per Program Keahlian — {{ $stats['active_year'] }}
                    </h3>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($stats['students_per_dept'] as $dept)
                            @php
                                $badgeConfig = match(strtoupper($dept->code)) {
                                    'PPLG' => ['icon_bg' => 'bg-blue-500/10 dark:bg-blue-500/20',    'text' => 'text-blue-500'],
                                    'TKJ'  => ['icon_bg' => 'bg-red-500/10 dark:bg-red-500/20',     'text' => 'text-red-500'],
                                    'AKL'  => ['icon_bg' => 'bg-amber-500/10 dark:bg-amber-500/20',   'text' => 'text-amber-500'],
                                    'MPLB' => ['icon_bg' => 'bg-purple-500/10 dark:bg-purple-500/20',  'text' => 'text-purple-500'],
                                    'PM'   => ['icon_bg' => 'bg-emerald-500/10 dark:bg-emerald-500/20', 'text' => 'text-emerald-500'],
                                    default => ['icon_bg' => 'bg-gray-100 dark:bg-white/[0.06]',      'text' => 'text-gray-500 dark:text-gray-400'],
                                };
                            @endphp
                            <div class="flex items-center gap-4 rounded-xl border border-gray-100 dark:border-amoled-border p-4 transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $badgeConfig['icon_bg'] }} shrink-0">
                                    <span class="text-sm font-bold {{ $badgeConfig['text'] }}">{{ $dept->code }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $dept->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $dept->total }} peserta didik</p>
                                </div>
                                <span class="text-lg font-bold {{ $badgeConfig['text'] }}">{{ $dept->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
