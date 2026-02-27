@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6"
         x-data="{
            selectAll: false,
            selectedIds: [],
            toggleAll() {
                const checkboxes = document.querySelectorAll('.cert-checkbox:not(:disabled)');
                if (this.selectAll) {
                    this.selectedIds = Array.from(checkboxes).map(cb => cb.value);
                } else {
                    this.selectedIds = [];
                }
            },
            isSelected(id) {
                return this.selectedIds.includes(id);
            },
            toggleOne(id) {
                if (this.selectedIds.includes(id)) {
                    this.selectedIds = this.selectedIds.filter(i => i !== id);
                } else {
                    this.selectedIds.push(id);
                }
                const checkboxes = document.querySelectorAll('.cert-checkbox:not(:disabled)');
                this.selectAll = this.selectedIds.length === checkboxes.length && checkboxes.length > 0;
            }
         }"
    >
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Cetak Sertifikat PKL
            </h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li>
                        <a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Cetak Sertifikat</li>
                </ol>
            </nav>
        </div>

        {{-- Filter Toolbar (GET) --}}
        <form method="GET" action="{{ route('certificates.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative sm:max-w-[220px]">
                <select
                    name="class"
                    onchange="this.form.submit()"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer"
                >
                    <option value="">— Pilih Kelas —</option>
                    @foreach($availableClasses as $class)
                        <option value="{{ $class }}" {{ $selectedClass === $class ? 'selected' : '' }} class="dark:bg-amoled-surface">{{ $class }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        @if($selectedClass)
            {{-- Summary Badge --}}
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 dark:text-gray-500">
                    Kelas: <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $selectedClass }}</span>
                    &middot;
                    Total: <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $certificates->count() }}</span> siswa
                </span>
            </div>

            {{-- Form Bulk Generate (Wraps the table) --}}
            <form method="POST" action="{{ route('certificates.generate') }}" id="generate-form">
                @csrf

                {{-- Input Fields --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3 mb-5">
                    {{-- Middle Number --}}
                    <div class="flex-1 sm:max-w-[200px]">
                        <label for="middle_number" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5">Nomor Surat Tengah</label>
                        <input
                            type="text"
                            id="middle_number"
                            name="middle_number"
                            placeholder="0621"
                            required
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
                    </div>

                    {{-- Issued Date --}}
                    <div class="flex-1 sm:max-w-[200px]">
                        <label for="issued_date" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5">Tanggal Terbit</label>
                        <input
                            type="date"
                            id="issued_date"
                            name="issued_date"
                            required
                            class="h-11 w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue"
                        />
                    </div>

                    {{-- Submit Button --}}
                    <button
                        type="submit"
                        :disabled="selectedIds.length === 0"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-school-blue py-2.5 px-5 text-sm font-semibold text-white hover:bg-blue-700 transition duration-150 shadow-sm h-11 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Terapkan & Cetak PDF (<span x-text="selectedIds.length">0</span> Siswa)
                    </button>
                </div>

                {{-- Table Container --}}
                <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">

                    {{-- Table (Desktop) --}}
                    <div class="hidden md:block max-h-[65vh] overflow-y-auto overflow-x-auto relative">
                        <table class="w-full table-auto">
                            <thead class="sticky top-0 z-10">
                                <tr class="bg-gray-50 text-left dark:bg-amoled-surface border-b border-gray-200 dark:border-amoled-border">
                                    <th class="py-3.5 px-4 xl:pl-8 w-12">
                                        <input
                                            type="checkbox"
                                            x-model="selectAll"
                                            @change="toggleAll()"
                                            class="w-4 h-4 rounded border-gray-300 text-school-blue focus:ring-school-blue/30 dark:border-amoled-border dark:bg-amoled-surface cursor-pointer"
                                        />
                                    </th>
                                    <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text w-24">NIS</th>
                                    <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">Nama Siswa</th>
                                    <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">Tempat PKL</th>
                                    <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text w-28">Kelas</th>
                                    <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center xl:pr-8 w-32">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($certificates as $cert)
                                    @php
                                        $studentName = $cert->internship->student->user->name ?? '-';
                                        $studentNis  = $cert->internship->student->nis ?? '-';
                                        $className   = $cert->internship->student->class_name ?? '-';
                                        $industryName = $cert->internship->industry->name ?? '-';
                                        $isDraft = $cert->status === 'draft';
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.04] transition duration-150 border-b border-gray-200 dark:border-amoled-border last:border-b-0">
                                        <td class="py-4 px-4 xl:pl-8">
                                            <input
                                                type="checkbox"
                                                name="certificate_ids[]"
                                                value="{{ $cert->id }}"
                                                class="cert-checkbox w-4 h-4 rounded border-gray-300 text-school-blue focus:ring-school-blue/30 dark:border-amoled-border dark:bg-amoled-surface cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                                                {{ $isDraft ? 'disabled' : '' }}
                                                :checked="isSelected('{{ $cert->id }}')"
                                                @change="toggleOne('{{ $cert->id }}')"
                                            />
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-sm text-gray-600 dark:text-gray-300 font-mono">{{ $studentNis }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <h5 class="font-medium text-gray-800 dark:text-white text-sm">{{ $studentName }}</h5>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $industryName }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ $className }}</span>
                                        </td>
                                        <td class="py-4 px-4 xl:pr-8 text-center">
                                            @if($cert->status === 'draft')
                                                <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-amber-500/10 text-amber-600 border-amber-500/20">
                                                    Belum Validasi
                                                </span>
                                            @elseif($cert->status === 'validated')
                                                <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20">
                                                    Siap Cetak
                                                </span>
                                            @elseif($cert->status === 'generated')
                                                <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-indigo-500/10 text-indigo-600 border-indigo-500/20">
                                                    Sudah Dicetak
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 px-4 text-center text-sm text-gray-500 dark:text-amoled-text">
                                            Tidak ada data sertifikat untuk kelas ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Card View (Mobile) --}}
                    <div class="md:hidden flex flex-col divide-y divide-gray-200 dark:divide-amoled-border">
                        {{-- Select All for Mobile --}}
                        <div class="p-4 flex items-center gap-3 bg-gray-50 dark:bg-amoled-surface border-b border-gray-200 dark:border-amoled-border">
                            <input
                                type="checkbox"
                                x-model="selectAll"
                                @change="toggleAll()"
                                class="w-4 h-4 rounded border-gray-300 text-school-blue focus:ring-school-blue/30 dark:border-amoled-border dark:bg-amoled-surface cursor-pointer"
                            />
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Pilih Semua (yang siap cetak)</span>
                        </div>

                        @forelse ($certificates as $cert)
                            @php
                                $studentName = $cert->internship->student->user->name ?? '-';
                                $studentNis  = $cert->internship->student->nis ?? '-';
                                $className   = $cert->internship->student->class_name ?? '-';
                                $industryName = $cert->internship->industry->name ?? '-';
                                $isDraft = $cert->status === 'draft';
                            @endphp
                            <div class="p-4 flex items-start gap-3">
                                <input
                                    type="checkbox"
                                    name="certificate_ids[]"
                                    value="{{ $cert->id }}"
                                    class="cert-checkbox w-4 h-4 mt-1 rounded border-gray-300 text-school-blue focus:ring-school-blue/30 dark:border-amoled-border dark:bg-amoled-surface cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                                    {{ $isDraft ? 'disabled' : '' }}
                                    :checked="isSelected('{{ $cert->id }}')"
                                    @change="toggleOne('{{ $cert->id }}')"
                                />
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between mb-1">
                                        <div>
                                            <h5 class="font-semibold text-gray-800 dark:text-white text-sm">{{ $studentName }}</h5>
                                            <p class="text-xs text-gray-500 dark:text-amoled-text mt-0.5 font-mono">{{ $studentNis }}</p>
                                        </div>
                                        @if($cert->status === 'draft')
                                            <span class="text-xs px-2.5 py-0.5 rounded-lg border font-semibold ml-2 whitespace-nowrap bg-amber-500/10 text-amber-600 border-amber-500/20">Belum Validasi</span>
                                        @elseif($cert->status === 'validated')
                                            <span class="text-xs px-2.5 py-0.5 rounded-lg border font-semibold ml-2 whitespace-nowrap bg-emerald-500/10 text-emerald-600 border-emerald-500/20">Siap Cetak</span>
                                        @elseif($cert->status === 'generated')
                                            <span class="text-xs px-2.5 py-0.5 rounded-lg border font-semibold ml-2 whitespace-nowrap bg-indigo-500/10 text-indigo-600 border-indigo-500/20">Sudah Dicetak</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-amoled-text">
                                        <svg class="w-3 h-3 inline -mt-0.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3"/></svg>
                                        {{ $industryName }}
                                    </p>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $className }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-sm text-gray-500 dark:text-amoled-text">
                                Tidak ada data sertifikat untuk kelas ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </form>
        @else
            {{-- Empty State: No class selected --}}
            <div class="flex flex-col items-center justify-center py-16 bg-white dark:bg-amoled-surface border border-dashed border-gray-200 dark:border-amoled-border rounded-2xl">
                <div class="p-4 rounded-full bg-blue-50 dark:bg-blue-500/10 text-school-blue mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Kelas Terlebih Dahulu</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center mt-1">Gunakan filter kelas di atas untuk menampilkan daftar siswa yang sertifikatnya siap dicetak.</p>
            </div>
        @endif
    </div>
@endsection
