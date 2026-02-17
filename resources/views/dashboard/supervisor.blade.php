@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Page Header -->
        <div class="min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</h2>
            <p class="text-sm text-gray-500 dark:text-amoled-text mt-1">
                Selamat datang kembali, <span class="font-semibold text-school-blue">{{ $user->name }}</span>
            </p>
        </div>

        <!-- Coming Soon Card -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-school-blue/10 dark:bg-school-blue/20 mb-5">
                        <svg class="w-8 h-8 text-school-blue" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Dashboard Pembimbing</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-sm">
                        Fitur dashboard pembimbing sedang dalam pengembangan. Anda akan dapat memantau siswa bimbingan di sini.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
