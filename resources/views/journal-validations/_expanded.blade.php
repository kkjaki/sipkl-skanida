{{-- Shared expanded content for both desktop & mobile views --}}
<div>
    <p class="text-[10px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em] mb-1">Kegiatan</p>
    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $journal->activity ?: '-' }}</p>
</div>

@if($journal->attachment_path)
    <div>
        <p class="text-[10px] font-black text-gray-400 dark:text-amoled-text uppercase tracking-[0.15em] mb-1.5">Lampiran</p>
        @if(preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $journal->attachment_path))
            <img src="{{ asset('storage/' . $journal->attachment_path) }}"
                 alt="Lampiran"
                 class="h-32 object-cover rounded-xl border border-gray-200 dark:border-amoled-border"
                 loading="lazy" width="200" height="128">
        @elseif(preg_match('/\.pdf$/i', $journal->attachment_path))
            <a href="{{ asset('storage/' . $journal->attachment_path) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-school-blue/10 text-school-blue text-xs font-bold hover:bg-school-blue/20 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Buka Surat Izin/Sakit
            </a>
        @else
            <a href="{{ asset('storage/' . $journal->attachment_path) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-300 text-xs font-medium hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                Unduh Lampiran
            </a>
        @endif
    </div>
@endif

@if($journal->rejection_note)
    <div class="p-3 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
        <p class="text-[10px] font-black text-red-500 uppercase tracking-[0.15em] mb-0.5">Alasan Penolakan</p>
        <p class="text-sm text-red-700 dark:text-red-400 leading-relaxed">{{ $journal->rejection_note }}</p>
    </div>
@endif

@if($journal->verified_at)
    <p class="text-[10px] text-gray-400 dark:text-gray-500">Divalidasi: {{ $journal->verified_at->format('d M Y, H:i') }}</p>
@endif
