@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Verifikasi Industri
            </h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li><a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Verifikasi Industri</li>
                </ol>
            </nav>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="flex w-full border-l-4 border-emerald-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('info'))
            <div class="flex w-full border-l-4 border-blue-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Search -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <div class="p-4 sm:p-6">
                <form action="{{ route('verification.index') }}" method="GET" class="flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama industri atau kota..."
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                    </div>
                    <button type="submit" class="h-11 inline-flex items-center justify-center rounded-lg bg-school-blue px-5 text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 shadow-sm">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Proposals Table -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            @if($proposals->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-amoled-border">
                                <th class="py-3.5 px-6 sm:px-8 font-medium text-gray-500 dark:text-amoled-text">Nama Industri</th>
                                <th class="py-3.5 px-4 font-medium text-gray-500 dark:text-amoled-text">Diajukan Oleh</th>
                                <th class="py-3.5 px-4 font-medium text-gray-500 dark:text-amoled-text">Tanggal</th>
                                <th class="py-3.5 px-4 font-medium text-gray-500 dark:text-amoled-text">Status</th>
                                <th class="py-3.5 px-6 sm:px-8 font-medium text-gray-500 dark:text-amoled-text text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                            @foreach($proposals as $proposal)
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
                                    <td class="py-3.5 px-4">
                                        @if($proposal->is_synced)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Sudah Sync
                                            </span>
                                        @elseif($proposal->status === 'blacklisted')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-red-50 dark:bg-red-500/10 px-2.5 py-1 text-xs font-medium text-red-600 dark:text-red-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 dark:bg-amber-500/10 px-2.5 py-1 text-xs font-medium text-amber-600 dark:text-amber-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Menunggu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 sm:px-8 text-right">
                                        <a href="{{ route('verification.show', $proposal->id) }}"
                                           class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition duration-150
                                                  {{ $proposal->is_synced
                                                      ? 'bg-gray-100 dark:bg-white/[0.06] text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/[0.1]'
                                                      : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 hover:bg-amber-500/20' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            {{ $proposal->is_synced ? 'Lihat' : 'Cek & Sync' }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="sm:hidden divide-y divide-gray-100 dark:divide-amoled-border">
                    @foreach($proposals as $proposal)
                        <div class="p-4 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 dark:text-white text-sm truncate">{{ $proposal->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $proposal->city }} &bull; {{ $proposal->created_at->format('d M Y') }}</p>
                                </div>
                                @if($proposal->is_synced)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-400 shrink-0">Sync</span>
                                @elseif($proposal->status === 'blacklisted')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 dark:bg-red-500/10 px-2 py-1 text-xs font-medium text-red-600 dark:text-red-400 shrink-0">Ditolak</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 dark:bg-amber-500/10 px-2 py-1 text-xs font-medium text-amber-600 dark:text-amber-400 shrink-0">Menunggu</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Oleh: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $proposal->studentSubmitter->name ?? '-' }}</span></p>
                                <a href="{{ route('verification.show', $proposal->id) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-amber-500/10 px-2.5 py-1.5 text-xs font-medium text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 shrink-0">
                                    {{ $proposal->is_synced ? 'Lihat' : 'Cek & Sync' }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($proposals->hasPages())
                    <div class="border-t border-gray-200 dark:border-amoled-border px-6 py-4">
                        {{ $proposals->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 text-center">
                    <svg class="mx-auto w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                        {{ $search ? 'Tidak ada pengajuan yang cocok dengan pencarian.' : 'Belum ada pengajuan industri dari siswa di jurusan Anda.' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
