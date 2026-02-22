@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Detail Pengajuan Industri</h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li><a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('verification.index') }}">Verifikasi</a></li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Detail</li>
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
        @if(session('error'))
            <div class="flex w-full border-l-4 border-red-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ session('error') }}</p>
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

        <!-- Student Info Banner -->
        @if($industry->student_submitter_id)
            <div class="flex w-full border-l-4 border-violet-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-violet-600 dark:text-violet-400 font-medium">
                        Diajukan oleh siswa: <strong>{{ $industry->studentSubmitter->name ?? '-' }}</strong>
                    </p>
                </div>
            </div>
        @endif

        <!-- Sync Status Banner -->
        @if($industry->is_synced)
            <div class="flex w-full border-l-4 border-emerald-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                        Industri ini sudah diverifikasi dan menunggu input kuota oleh Admin/Humas.
                    </p>
                </div>
            </div>
        @endif

        <!-- Profil Industri (Read-Only) -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Profil Industri</h3>
            </div>
            <div class="p-6 sm:p-8">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Nama Industri</dt>
                        <dd class="mt-0.5 text-sm font-medium text-gray-800 dark:text-white">{{ $industry->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Kota</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->city }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Alamat</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Contact Person</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->contact_person ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Email</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">No. Telepon</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->phone ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Action Buttons -->
        @if(!$industry->is_synced && $industry->status !== 'blacklisted')
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Keputusan Verifikasi</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Tentukan apakah industri ini sesuai dengan kurikulum program keahlian Anda.</p>
                </div>
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- Approve Button -->
                        <form action="{{ route('verification.approve', $industry->id) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Apakah industri ini sesuai dengan kurikulum? Data akan disinkronisasi dan menunggu input kuota oleh Admin.')">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2.5 rounded-xl bg-emerald-600 py-3 px-6 text-center text-sm font-medium text-white hover:bg-emerald-700 transition duration-150 ease-in-out shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Sesuai Kurikulum (Sync)
                            </button>
                        </form>

                        <!-- Reject Button -->
                        <form action="{{ route('verification.reject', $industry->id) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Apakah Anda yakin menolak pengajuan ini? Industri akan ditandai sebagai tidak sesuai kurikulum.')">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2.5 rounded-xl border-2 border-red-200 dark:border-red-500/30 bg-transparent py-3 px-6 text-center text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition duration-150 ease-in-out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                Tolak (Tidak Sesuai Kurikulum)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Unreject Button (for blacklisted proposals) -->
        @if(!$industry->is_synced && $industry->status === 'blacklisted')
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Revisi Keputusan</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Pengajuan ini sebelumnya telah ditolak. Anda dapat mencabut penolakan untuk meninjau ulang.</p>
                </div>
                <div class="p-6 sm:p-8">
                    <form action="{{ route('verification.unreject', $industry->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin mencabut penolakan? Pengajuan akan kembali ke status menunggu verifikasi.')">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2.5 rounded-xl border-2 border-amber-300 dark:border-amber-500/30 bg-transparent py-3 px-6 text-center text-sm font-medium text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Cabut Penolakan (Tinjau Ulang)
                        </button>
                    </form>
                </div>
            </div>
        @endif
        <!-- Unsync Button (for synced proposals, revoke approval) -->
        @if($industry->is_synced)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Revisi Keputusan</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Anda sebelumnya telah menyetujui industri ini. Sinkronisasi dapat dicabut selama belum ada siswa yang tertaut.</p>
                </div>
                <div class="p-6 sm:p-8">
                    <form action="{{ route('verification.unsync', $industry->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin mencabut sinkronisasi? Industri akan kembali ke status menunggu verifikasi.')">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2.5 rounded-xl border-2 border-amber-300 dark:border-amber-500/30 bg-transparent py-3 px-6 text-center text-sm font-medium text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Cabut Sinkronisasi
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div>
            <a href="{{ route('verification.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-amoled-border bg-transparent py-2.5 px-5 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 ease-in-out">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>
@endsection
