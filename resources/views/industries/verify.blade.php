@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Verifikasi Pengajuan Industri</h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li><a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('industries.index') }}">Industri</a></li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Verifikasi</li>
                </ol>
            </nav>
        </div>

        <!-- Student Info Banner -->
        @if($industry->student_submitter_id)
            <div class="flex w-full border-l-4 border-violet-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-violet-600 dark:text-violet-400 font-medium">
                        Diajukan oleh siswa: <strong>{{ $industry->studentSubmitter->name ?? '-' }}</strong>
                        &bull; Metode pengiriman: <strong>{{ $industry->delivery_method_proposal === 'school' ? 'Diantar Sekolah' : 'Antar Sendiri' }}</strong>
                    </p>
                </div>
            </div>
        @endif

        <!-- Profil Industri (Read-Only) -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Data Industri (Read-only)</h3>
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

        <!-- Allocation Form -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <form action="{{ route('industries.approve', $industry->id) }}" method="POST" id="approveForm">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Alokasi Kuota per Jurusan</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Hubungi industri terlebih dahulu untuk mengetahui berapa siswa yang dapat diterima. Minimal total kuota harus 1.</p>
                </div>
                <div class="p-6 sm:p-8 space-y-4 border-b border-gray-200 dark:border-amoled-border">
                    @error('quotas')
                        <div class="flex w-full border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 px-4 py-3 rounded-r-xl">
                            <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                        </div>
                    @enderror

                    @foreach($departments as $dept)
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                            <label for="quota_{{ $dept->id }}" class="sm:w-1/2 text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ $dept->name }} <span class="text-xs text-gray-400">({{ $dept->code }})</span>
                            </label>
                            <input type="number" id="quota_{{ $dept->id }}" name="quotas[{{ $dept->id }}]"
                                   value="{{ old('quotas.' . $dept->id, $existingQuotas[$dept->id] ?? 0) }}" min="0"
                                   class="h-11 w-full sm:w-32 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                            @error('quotas.' . $dept->id)
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Reject (Left) -->
                        <div>
                            <button type="button" onclick="document.getElementById('rejectForm').submit()"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 dark:border-red-500/30 bg-transparent py-2.5 px-6 text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                Tolak Pengajuan
                            </button>
                        </div>

                        <!-- Approve + Cancel (Right) -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('industries.index') }}"
                               class="inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-amoled-border bg-transparent py-2.5 px-6 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 ease-in-out">
                                Kembali
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-emerald-600 py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-emerald-700 transition duration-150 ease-in-out shadow-sm">
                                <svg class="w-4 h-4 fill-current" width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                Setujui & Simpan Kuota
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Hidden Reject Form -->
            <form id="rejectForm" action="{{ route('industries.reject', $industry->id) }}" method="POST" class="hidden" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan ini? Industri akan ditandai sebagai Blacklisted.')">
                @csrf
                @method('PUT')
            </form>
        </div>
    </div>
@endsection
