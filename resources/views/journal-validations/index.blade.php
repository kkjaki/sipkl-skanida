@extends('layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Validasi Logbook Harian</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Daftar siswa bimbingan Anda</p>
    </div>

    @if($internships->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 sm:py-20 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-3xl">
            <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 mb-4">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Siswa Bimbingan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center">Anda belum memiliki siswa yang di-plot sebagai bimbingan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-5">
            @foreach($internships as $internship)
                <a href="{{ route('supervisor.journal-validations.show', $internship) }}"
                   class="group flex flex-col bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-2xl overflow-hidden transition-all duration-200 hover:shadow-xl hover:shadow-school-blue/5 hover:-translate-y-0.5">
                    <div class="p-4 sm:p-5 flex-1">
                        <div class="flex justify-between items-start gap-2 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-school-blue/10 flex items-center justify-center text-school-blue font-black text-base flex-shrink-0">
                                {{ substr($internship->student->user->name, 0, 1) }}
                            </div>

                            @if($internship->pending_count > 0)
                                <span class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400 animate-pulse">
                                    {{ $internship->pending_count }} Pending
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    All Cleared
                                </span>
                            @endif
                        </div>

                        <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-1 leading-tight">
                            {{ $internship->student->user->name }}
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-1">
                            {{ $internship->student->class_name }} &bull; NIS: {{ $internship->student->nis }}
                        </p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 flex items-start gap-1.5 mt-2">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3"/></svg>
                            <span class="line-clamp-1">{{ $internship->industry->name ?? '-' }}</span>
                        </p>
                    </div>

                    <div class="px-4 sm:px-5 py-2.5 sm:py-3 border-t border-gray-100 dark:border-amoled-border bg-gray-50/50 dark:bg-white/[0.01]">
                        <span class="text-sm font-bold text-school-blue flex items-center justify-center gap-2 group-hover:gap-3 transition-all">
                            Lihat Logbook
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection
