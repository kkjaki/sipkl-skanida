@extends('layouts.app')

@section('content')
<div x-data="{
    showConfirm: false,
    scores: {
        @foreach($evaluationIndicators as $indicator)
            @php
                $ex = $assessmentScores->get($indicator->id);
                $indVal = old("scores.{$indicator->id}.industry_score", $ex->score_industry ?? null);
                $schVal = old("scores.{$indicator->id}.supervisor_score", $ex->score_school ?? null);
            @endphp
            {{ $indicator->id }}: {
                industry: {{ is_numeric($indVal) ? $indVal : 'null' }},
                school: {{ is_numeric($schVal) ? $schVal : 'null' }}
            },
        @endforeach
    },

    avg(id) {
        let s = this.scores[id];
        let i = parseFloat(s.industry);
        let g = parseFloat(s.school);
        let hasI = !isNaN(i);
        let hasG = !isNaN(g);
        if (hasI && hasG) return ((i + g) / 2).toFixed(1);
        if (hasI) return i.toFixed(1);
        if (hasG) return g.toFixed(1);
        return '-';
    },

    grandAvg() {
        let sum = 0, count = 0;
        for (let id in this.scores) {
            let a = this.avg(id);
            if (a !== '-') { sum += parseFloat(a); count++; }
        }
        return count > 0 ? (sum / count).toFixed(1) : '-';
    },

    avgClass(val) {
        if (val === '-') return 'text-gray-400 dark:text-gray-500';
        let n = parseFloat(val);
        if (n >= 90) return 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400';
        if (n >= 80) return 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400';
        return 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400';
    },

    emptyCount() {
        let count = 0;
        for (let id in this.scores) {
            let s = this.scores[id];
            if (s.industry === null || s.industry === '' || isNaN(parseFloat(s.industry))) count++;
            if (s.school === null || s.school === '' || isNaN(parseFloat(s.school))) count++;
        }
        return count;
    },

    submitForm() {
        this.showConfirm = false;
        this.$refs.scoreForm.submit();
    }
}" class="space-y-4 sm:space-y-6">

    {{-- Back Link --}}
    <a href="{{ route('supervisor.assessments.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-school-blue transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    {{-- Student Info Card --}}
    <div class="bg-white dark:bg-amoled-surface border border-gray-100 dark:border-amoled-border rounded-2xl p-4 sm:p-5">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-school-blue/10 flex items-center justify-center text-school-blue font-black text-base flex-shrink-0">
                {{ substr($internship->student->user->name, 0, 1) }}
            </div>
            <div class="min-w-0">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white truncate">Input Nilai — {{ $internship->student->user->name }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $internship->student->class_name }} &bull; {{ $internship->student->nis }} &bull; {{ $internship->industry->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center gap-2.5 p-3 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 dark:bg-green-500/10 dark:text-green-400 dark:border-green-800/40" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
            <p><span class="font-bold">Berhasil!</span> {{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center gap-2.5 p-3 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800/40" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/></svg>
            <p><span class="font-bold">Gagal!</span> {{ session('error') }}</p>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="p-3 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50 dark:bg-red-500/10 dark:text-red-400 dark:border-red-800/40" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($evaluationIndicators->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 sm:py-20 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-3xl">
            <div class="p-4 rounded-full bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-500 mb-4">
                <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Indikator Penilaian</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center">Hubungi WKS Kurikulum untuk menambahkan indikator penilaian terlebih dahulu.</p>
        </div>
    @else
        <form x-ref="scoreForm" action="{{ route('supervisor.assessments.update', $internship) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Desktop Table --}}
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface overflow-hidden">
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-amoled-surface dark:text-gray-400 border-b border-gray-200 dark:border-amoled-border">
                            <tr>
                                <th class="px-5 py-3 font-semibold w-14">No</th>
                                <th class="px-5 py-3 font-semibold">Indikator Penilaian</th>
                                <th class="px-5 py-3 font-semibold text-center w-36">Nilai Industri</th>
                                <th class="px-5 py-3 font-semibold text-center w-36">Nilai Guru</th>
                                <th class="px-5 py-3 font-semibold text-center w-32">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                            @foreach($evaluationIndicators as $index => $indicator)
                                @php $existingScore = $assessmentScores->get($indicator->id); @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300 font-medium">{{ $indicator->name }}</td>
                                    <td class="px-5 py-3.5">
                                        <input type="hidden" name="scores[{{ $indicator->id }}][indicator_id]" value="{{ $indicator->id }}">
                                        <input type="number" name="scores[{{ $indicator->id }}][industry_score]"
                                               x-model.number="scores[{{ $indicator->id }}].industry"
                                               min="0" max="100" step="1" placeholder="0-100"
                                               class="w-full max-w-[120px] mx-auto block text-center rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <input type="number" name="scores[{{ $indicator->id }}][supervisor_score]"
                                               x-model.number="scores[{{ $indicator->id }}].school"
                                               min="0" max="100" step="1" placeholder="0-100"
                                               class="w-full max-w-[120px] mx-auto block text-center rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="inline-flex items-center justify-center min-w-[48px] px-2.5 py-1 rounded-lg text-sm font-bold"
                                              :class="avgClass(avg({{ $indicator->id }}))"
                                              x-text="avg({{ $indicator->id }})"></span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-200 dark:border-amoled-border bg-gray-50/80 dark:bg-white/[0.02]">
                            <tr>
                                <td colspan="4" class="px-5 py-3.5 text-right font-bold text-gray-900 dark:text-white text-sm uppercase tracking-wide">Rata-Rata Keseluruhan</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[48px] px-3 py-1.5 rounded-xl text-sm font-black"
                                          :class="avgClass(grandAvg())"
                                          x-text="grandAvg()"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Mobile Card List --}}
                <div class="sm:hidden divide-y divide-gray-100 dark:divide-amoled-border">
                    @foreach($evaluationIndicators as $index => $indicator)
                        @php $existingScore = $assessmentScores->get($indicator->id); @endphp
                        <div class="p-4 space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-7 h-7 rounded-lg bg-school-blue/10 flex items-center justify-center text-school-blue text-xs font-bold flex-shrink-0">{{ $index + 1 }}</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $indicator->name }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold flex-shrink-0"
                                      :class="avgClass(avg({{ $indicator->id }}))"
                                      x-text="'Akhir: ' + avg({{ $indicator->id }})"></span>
                            </div>
                            <input type="hidden" name="scores[{{ $indicator->id }}][indicator_id]" value="{{ $indicator->id }}">
                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nilai Industri</label>
                                    <input type="number" name="scores[{{ $indicator->id }}][industry_score]"
                                           x-model.number="scores[{{ $indicator->id }}].industry"
                                           min="0" max="100" step="1" placeholder="0-100"
                                           class="w-full text-center rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Nilai Guru</label>
                                    <input type="number" name="scores[{{ $indicator->id }}][supervisor_score]"
                                           x-model.number="scores[{{ $indicator->id }}].school"
                                           min="0" max="100" step="1" placeholder="0-100"
                                           class="w-full text-center rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Mobile Grand Average --}}
                    <div class="p-4 bg-gray-50/80 dark:bg-white/[0.02]">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">Rata-Rata</span>
                            <span class="px-3 py-1 rounded-xl text-sm font-black"
                                  :class="avgClass(grandAvg())"
                                  x-text="grandAvg()"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end mt-4 sm:mt-6">
                <button type="button" @click="showConfirm = true"
                        class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl bg-school-blue hover:bg-school-blue/90 text-white font-bold text-sm transition-colors shadow-lg shadow-school-blue/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan Nilai
                </button>
            </div>
        </form>
    @endif

    {{-- Confirmation Modal --}}
    <div x-show="showConfirm" class="fixed inset-0 z-[100]"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-black/70" @click="showConfirm = false"></div>

            <div class="relative w-full sm:max-w-sm bg-white dark:bg-amoled-surface rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-amoled-border"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                 x-transition:enter-end="translate-y-0 scale-100 opacity-100">

                <div class="p-5 sm:p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-500/15 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Konfirmasi Simpan Nilai</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Rata-rata keseluruhan: <span class="font-bold text-gray-900 dark:text-white" x-text="grandAvg()"></span></p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">Pastikan semua nilai sudah benar sebelum menyimpan.</p>

                    <div x-show="emptyCount() > 0" x-cloak
                         class="mt-3 flex items-center gap-2 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-800/40 text-amber-700 dark:text-amber-400 text-xs text-left">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/></svg>
                        <span>Masih ada <strong x-text="emptyCount()"></strong> kolom nilai yang belum diisi.</span>
                    </div>
                </div>

                <div class="px-5 pb-5 sm:px-6 sm:pb-6 grid grid-cols-2 gap-2">
                    <button type="button" @click="showConfirm = false"
                            class="h-10 rounded-xl bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-300 dark:hover:bg-white/20 transition-colors">
                        Periksa Lagi
                    </button>
                    <button type="button" @click="submitForm()"
                            class="h-10 rounded-xl bg-school-blue hover:bg-school-blue/90 text-white font-bold text-sm transition-colors">
                        Ya, Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
