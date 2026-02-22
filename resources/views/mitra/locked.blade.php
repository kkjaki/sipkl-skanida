@extends('layouts.mitra')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface p-8 sm:p-12 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-500/10 mb-6">
            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Tautan Terkunci</h2>
        <p class="text-gray-500 dark:text-amoled-text leading-relaxed max-w-sm mx-auto mb-8">
            Data kesanggupan dan PIC sertifikat untuk <strong>{{ $industry->name }}</strong> sudah berhasil dikirim dan saat ini sedang dalam proses verifikasi oleh tim Hubin.
        </p>

        <!-- Read Only Summary -->
        <div class="bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-white/[0.05] rounded-2xl p-6 mb-8 text-left">
            <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4">Ringkasan Data PIC:</h3>
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 border-b border-gray-100 dark:border-white/[0.05] pb-3">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Nama PIC</span>
                    <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $industry->pic_name }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 border-b border-gray-100 dark:border-white/[0.05] pb-3">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Jabatan</span>
                    <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $industry->pic_position }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">NIP</span>
                    <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $industry->nip ?? '-' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Contact Button -->
        <div class="space-y-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Ada kesalahan pengetikan? Silakan hubungi admin Humas kami untuk revisi:
            </p>
            <a href="https://wa.me/6281226162312?text=Halo%20Admin%20Hubin%20Skanida,%20saya%20dari%20{{ urlencode($industry->name) }}%20ingin%20merevisi%20data%20PIC/Kuota%20PKL." 
               target="_blank"
               class="inline-flex items-center justify-center gap-3 rounded-xl bg-[#25D366] py-3.5 px-8 text-sm font-bold text-white hover:bg-[#20ba59] hover:shadow-lg hover:shadow-green-500/20 transition-all duration-200">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                </svg>
                <span>Hubungi Humas (WhatsApp)</span>
            </a>
        </div>
        
        <div class="mt-12 pt-8 border-t border-gray-100 dark:border-white/[0.05]">
            <p class="text-xs text-gray-400 dark:text-gray-600 italic">
                Sistem Penempatan PKL (SIPKL) - SKANIDA
            </p>
        </div>
    </div>
@endsection
