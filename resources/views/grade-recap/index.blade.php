@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6"
         x-data="{
            search: '',
            selectedClass: '',
         }"
    >
        {{-- Top Controls --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Rekap Nilai PKL
            </h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li>
                        <a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Rekap Nilai</li>
                </ol>
            </nav>
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            {{-- Search --}}
            <div class="relative flex-1 sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Cari nama atau NIS..."
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                />
            </div>

            {{-- Filter Kelas --}}
            <div class="relative sm:max-w-[220px]">
                <select
                    x-model="selectedClass"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer"
                >
                    <option value="" class="dark:bg-amoled-surface">Semua Kelas</option>
                    @foreach($availableClasses as $class)
                        <option value="{{ $class }}" class="dark:bg-amoled-surface">{{ $class }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset --}}
            <button
                x-show="search !== '' || selectedClass !== ''"
                x-cloak
                @click="search = ''; selectedClass = ''"
                type="button"
                class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 dark:border-amoled-border py-2.5 px-4 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 h-11"
            >
                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Reset
            </button>

            {{-- Spacer --}}
            <div class="hidden sm:block sm:flex-1"></div>

            {{-- Export Button --}}
            <a href="{{ route('grade-recap.export') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 py-2.5 px-5 text-sm font-medium text-white hover:bg-emerald-700 transition duration-150 shadow-sm h-11">
                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </a>
        </div>

        {{-- Summary Badge --}}
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400 dark:text-gray-500">
                Total: <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $internships->count() }}</span> siswa
            </span>
        </div>

        {{-- Content Container --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">

            {{-- Table (Desktop) --}}
            <div class="hidden md:block max-h-[70vh] overflow-y-auto overflow-x-auto relative">
                <table class="w-full table-auto">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-gray-50 text-left dark:bg-amoled-surface border-b border-gray-200 dark:border-amoled-border">
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text xl:pl-8 w-14">
                                No
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text w-24">
                                NIS
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                Nama Siswa
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text w-28">
                                Kelas
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                Tempat Magang
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center w-32">
                                <span class="block">Rerata</span>
                                <span class="text-[11px] font-normal text-gray-400">Industri</span>
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center w-32">
                                <span class="block">Rerata</span>
                                <span class="text-[11px] font-normal text-gray-400">Sekolah</span>
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center xl:pr-8 w-28">
                                Nilai Akhir
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($internships as $index => $internship)
                            @php
                                $studentName = $internship->student->user->name ?? '-';
                                $studentNis  = $internship->student->nis ?? '-';
                                $className   = $internship->student->class_name ?? '-';
                                $industryName = $internship->industry->name ?? '-';

                                $avgIndustry = $internship->avg_industry;
                                $avgSchool   = $internship->avg_school;
                                $finalScore  = ($avgIndustry !== null && $avgSchool !== null)
                                    ? round(($avgIndustry + $avgSchool) / 2, 2)
                                    : null;
                            @endphp
                            <tr
                                data-name="{{ strtolower($studentName) }}"
                                data-nis="{{ $studentNis }}"
                                data-class="{{ $className }}"
                                x-show="
                                    (selectedClass === '' || $el.dataset.class === selectedClass) &&
                                    (search === '' || $el.dataset.name.includes(search.toLowerCase()) || $el.dataset.nis.includes(search.toLowerCase()))
                                "
                                x-transition.opacity.duration.150ms
                                class="hover:bg-gray-50 dark:hover:bg-white/[0.04] transition duration-150 border-b border-gray-200 dark:border-amoled-border last:border-b-0"
                            >
                                <td class="py-4 px-4 xl:pl-8">
                                    <span class="text-sm text-gray-500 dark:text-amoled-text">{{ $loop->iteration }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300 font-mono">{{ $studentNis }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <h5 class="font-medium text-gray-800 dark:text-white text-sm">{{ $studentName }}</h5>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $className }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $industryName }}</span>
                                </td>
                                {{-- Rerata Industri --}}
                                <td class="py-4 px-4 text-center">
                                    @if($avgIndustry !== null)
                                        @php
                                            $colorIndustry = match(true) {
                                                $avgIndustry >= 90 => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                                $avgIndustry < 90 && $avgIndustry >= 80 => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                                default            => 'bg-red-500/10 text-red-600 border-red-500/20',
                                            };
                                        @endphp
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border {{ $colorIndustry }}">
                                            {{ number_format($avgIndustry, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-gray-100 text-gray-400 border-gray-200 dark:bg-white/[0.06] dark:text-gray-500 dark:border-amoled-border">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                {{-- Rerata Sekolah --}}
                                <td class="py-4 px-4 text-center">
                                    @if($avgSchool !== null)
                                        @php
                                            $colorSchool = match(true) {
                                                $avgIndustry >= 90 => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                                $avgIndustry < 90 && $avgIndustry >= 80 => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                                default            => 'bg-red-500/10 text-red-600 border-red-500/20',
                                            };
                                        @endphp
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border {{ $colorSchool }}">
                                            {{ number_format($avgSchool, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-gray-100 text-gray-400 border-gray-200 dark:bg-white/[0.06] dark:text-gray-500 dark:border-amoled-border">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                {{-- Nilai Akhir --}}
                                <td class="py-4 px-4 xl:pr-8 text-center">
                                    @if($finalScore !== null)
                                        @php
                                            $colorFinal = match(true) {
                                                $avgIndustry >= 90 => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                                $avgIndustry < 90 && $avgIndustry >= 80 => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                                default            => 'bg-red-500/10 text-red-600 border-red-500/20',
                                            };
                                        @endphp
                                        <span class="inline-block rounded-lg px-2.5 py-1 text-sm font-bold border {{ $colorFinal }}">
                                            {{ number_format($finalScore, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-gray-100 text-gray-400 border-gray-200 dark:bg-white/[0.06] dark:text-gray-500 dark:border-amoled-border">
                                            -
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 px-4 text-center text-sm text-gray-500 dark:text-amoled-text">
                                    Belum ada data magang yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Card View (Mobile) --}}
            <div class="md:hidden flex flex-col divide-y divide-gray-200 dark:divide-amoled-border">
                @forelse ($internships as $index => $internship)
                    @php
                        $studentName = $internship->student->user->name ?? '-';
                        $studentNis  = $internship->student->nis ?? '-';
                        $className   = $internship->student->class_name ?? '-';
                        $industryName = $internship->industry->name ?? '-';

                        $avgIndustry = $internship->avg_industry;
                        $avgSchool   = $internship->avg_school;
                        $finalScore  = ($avgIndustry !== null && $avgSchool !== null)
                            ? round(($avgIndustry + $avgSchool) / 2, 2)
                            : null;
                    @endphp
                    <div
                        class="p-4"
                        data-name="{{ strtolower($studentName) }}"
                        data-nis="{{ $studentNis }}"
                        data-class="{{ $className }}"
                        x-show="
                            (selectedClass === '' || $el.dataset.class === selectedClass) &&
                            (search === '' || $el.dataset.name.includes(search.toLowerCase()) || $el.dataset.nis.includes(search.toLowerCase()))
                        "
                        x-transition.opacity.duration.150ms
                    >
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h5 class="font-semibold text-gray-800 dark:text-white text-sm">{{ $studentName }}</h5>
                                <p class="text-xs text-gray-500 dark:text-amoled-text mt-0.5 font-mono">{{ $studentNis }}</p>
                            </div>
                            <span class="text-xs px-2.5 py-0.5 rounded-lg border font-semibold ml-2 whitespace-nowrap bg-blue-500/10 text-blue-500 border-blue-500/20">{{ $className }}</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-amoled-text mb-3">
                            <svg class="w-3 h-3 inline -mt-0.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3"/></svg>
                            {{ $industryName }}
                        </p>
                        <div class="grid grid-cols-3 gap-2">
                            {{-- Industri --}}
                            <div class="text-center">
                                <span class="block text-[10px] text-gray-400 dark:text-gray-500 uppercase mb-1">Industri</span>
                                @if($avgIndustry !== null)
                                    @php
                                        $colorIndustry = match(true) {
                                            $avgIndustry >= 90 => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                            $avgIndustry < 90 && $avgIndustry >= 80 => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                            default            => 'bg-red-500/10 text-red-600 border-red-500/20',
                                        };
                                    @endphp
                                    <span class="inline-block rounded-lg px-2 py-0.5 text-xs font-semibold border {{ $colorIndustry }}">{{ number_format($avgIndustry, 2) }}</span>
                                @else
                                    <span class="inline-block rounded-lg px-2 py-0.5 text-[10px] font-semibold border bg-gray-100 text-gray-400 border-gray-200 dark:bg-white/[0.06] dark:text-gray-500 dark:border-amoled-border">N/A</span>
                                @endif
                            </div>
                            {{-- Sekolah --}}
                            <div class="text-center">
                                <span class="block text-[10px] text-gray-400 dark:text-gray-500 uppercase mb-1">Sekolah</span>
                                @if($avgSchool !== null)
                                    @php
                                        $colorSchool = match(true) {
                                            $avgIndustry >= 90 => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                            $avgIndustry < 90 && $avgIndustry >= 80 => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                            default            => 'bg-red-500/10 text-red-600 border-red-500/20',
                                        };
                                    @endphp
                                    <span class="inline-block rounded-lg px-2 py-0.5 text-xs font-semibold border {{ $colorSchool }}">{{ number_format($avgSchool, 2) }}</span>
                                @else
                                    <span class="inline-block rounded-lg px-2 py-0.5 text-[10px] font-semibold border bg-gray-100 text-gray-400 border-gray-200 dark:bg-white/[0.06] dark:text-gray-500 dark:border-amoled-border">N/A</span>
                                @endif
                            </div>
                            {{-- Nilai Akhir --}}
                            <div class="text-center">
                                <span class="block text-[10px] text-gray-400 dark:text-gray-500 uppercase mb-1">Akhir</span>
                                @if($finalScore !== null)
                                    @php
                                        $colorFinal = match(true) {
                                            $avgIndustry >= 90 => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                            $avgIndustry < 90 && $avgIndustry >= 80 => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                            default            => 'bg-red-500/10 text-red-600 border-red-500/20',
                                        };
                                    @endphp
                                    <span class="inline-block rounded-lg px-2 py-0.5 text-sm font-bold border {{ $colorFinal }}">{{ number_format($finalScore, 2) }}</span>
                                @else
                                    <span class="inline-block rounded-lg px-2 py-0.5 text-[10px] font-semibold border bg-gray-100 text-gray-400 border-gray-200 dark:bg-white/[0.06] dark:text-gray-500 dark:border-amoled-border">N/A</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-500 dark:text-amoled-text">
                        Belum ada data magang yang tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
