@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Pengajuan Lokasi PKL</h2>
                <p class="text-sm text-gray-500 dark:text-amoled-text mt-1">Riwayat pengajuan lokasi PKL Anda</p>
            </div>
            @if ($canPropose)
                <a href="{{ route('student.proposals.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                    <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Lokasi Baru
                </a>
            @endif
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div
                class="flex w-full border-l-4 border-emerald-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" width="20" height="20" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div
                class="flex w-full border-l-4 border-school-blue bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-school-blue shrink-0" width="20" height="20" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-school-blue font-medium">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Proposals List -->
        @forelse($proposals as $proposal)
            <div
                class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <!-- Industry Info -->
                        <div class="flex-1 min-w-0 space-y-3">
                            <!-- Name & Badge -->
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h3 class="text-base font-bold text-gray-800 dark:text-white">{{ $proposal->name }}</h3>

                                {{-- Status Badge: Strict 4-condition mapping --}}
                                @if (!$proposal->is_synced)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold bg-yellow-500/10 text-yellow-600 dark:bg-yellow-500/20 dark:text-yellow-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                        Menunggu Review
                                    </span>
                                @elseif($proposal->status === 'blacklisted')
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold bg-red-900/10 text-red-600 dark:bg-red-900/20 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                        Ditolak / Blacklist
                                    </span>
                                @elseif($proposal->status === 'full')
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold bg-red-500/10 text-red-500 dark:bg-red-500/20 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Kuota Penuh
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Verified (Open)
                                    </span>
                                @endif
                            </div>

                            <!-- Detail Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Lokasi -->
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 mt-0.5 shrink-0" width="16"
                                        height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">Lokasi</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 truncate">
                                            {{ $proposal->address }}, {{ $proposal->city }}</p>
                                    </div>
                                </div>

                                <!-- Kontak -->
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 mt-0.5 shrink-0" width="16"
                                        height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div class="min-w-0">
                                        <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">Kontak</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $proposal->contact_person ?: '-' }}
                                            @if ($proposal->phone)
                                                <span class="text-gray-400 dark:text-gray-500">·</span>
                                                {{ $proposal->phone }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            @if (!$proposal->is_synced)
                                <div class="mt-4 flex items-center gap-3 border-t border-gray-100 dark:border-white/5 pt-3">
                                    <a href="{{ route('student.proposals.edit', $proposal) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition duration-150 dark:bg-white/[0.06] dark:text-gray-300 dark:hover:bg-white/[0.1]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('student.proposals.destroy', $proposal) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus pengajuan ini?');"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition duration-150 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div
                class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="flex flex-col items-center justify-center py-14 px-6 text-center">
                    @if ($hasPlotting)
                        {{-- Siswa sudah di-plot, tidak pernah mengajukan sendiri --}}
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 mb-4">
                            <svg class="w-7 h-7 text-emerald-500" width="28" height="28" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Anda sudah ditempatkan PKL</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-xs">Anda telah ditempatkan di lokasi
                            PKL oleh Kepala Program. Tidak perlu mengajukan lokasi baru.</p>
                    @else
                        {{-- Siswa belum di-plot dan belum pernah mengajukan --}}
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-white/[0.06] mb-4">
                            <svg class="w-7 h-7 text-gray-400 dark:text-gray-500" width="28" height="28"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Belum ada pengajuan</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-xs">Anda belum mengajukan lokasi PKL.
                            Klik tombol di atas untuk memulai pengajuan.</p>
                    @endif
                </div>
            </div>
        @endforelse
    </div>
@endsection
