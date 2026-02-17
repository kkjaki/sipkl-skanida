@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Input Kuota Industri</h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li><a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('industries.index') }}">Industri</a></li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Input Kuota</li>
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
                        — Sudah diverifikasi oleh Kaprog
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
            <form action="{{ route('industries.storeAllocation', $industry->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Alokasi Kuota per Jurusan</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Hubungi industri untuk mengetahui berapa siswa yang dapat diterima. Minimal total kuota harus 1.</p>
                </div>
                <div class="p-6 sm:p-8 space-y-4 border-b border-gray-200 dark:border-amoled-border">
                    @if($hasOngoingInternships)
                        <div class="flex w-full border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 px-4 py-3 rounded-r-xl">
                            <div class="w-full flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                                    Kuota tidak dapat diubah karena terdapat siswa yang sedang menjalani PKL (ongoing) di industri ini.
                                </p>
                            </div>
                        </div>
                    @endif

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
                                   {{ $hasOngoingInternships ? 'disabled' : '' }}
                                   class="h-11 w-full sm:w-32 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue {{ $hasOngoingInternships ? 'opacity-50 cursor-not-allowed' : '' }}" />
                            @error('quotas.' . $dept->id)
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('industries.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-amoled-border bg-transparent py-2.5 px-6 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 ease-in-out">
                            Kembali
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                            <svg class="w-4 h-4 fill-current" width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            Simpan Kuota
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
