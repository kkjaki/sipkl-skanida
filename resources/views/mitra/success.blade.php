@extends('layouts.mitra')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface p-8 sm:p-12 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-500/10 mb-6">
            <svg class="w-10 h-10 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Terima Kasih!</h2>
        <p class="text-gray-500 dark:text-amoled-text leading-relaxed max-w-sm mx-auto">
            Data kesanggupan dan PIC sertifikat perusahaan Anda telah kami simpan dengan aman.
        </p>
        
        <div class="mt-8 pt-8 border-t border-gray-100 dark:border-white/[0.05]">
            <p class="text-xs text-gray-400 dark:text-gray-600">
                Informasi ini akan digunakan oleh pihak sekolah untuk koordinasi penempatan siswa PKL.
            </p>
        </div>
    </div>
@endsection
