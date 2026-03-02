@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        {{-- Page Header --}}
        <div class="min-h-[44px]">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Dashboard Pembimbing</h1>
            <p class="text-sm text-gray-500 dark:text-amoled-text mt-1">
                Selamat datang kembali, <span class="font-semibold text-school-blue">{{ $user->name }}</span>
            </p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            {{-- Total Siswa Bimbingan --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Siswa Bimbingan</p>
                        <h3 class="mt-2 text-3xl font-bold text-school-blue">{{ $totalStudents }}</h3>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">siswa yang dibimbing</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-school-blue/10 dark:bg-school-blue/20 shrink-0">
                        <svg class="w-6 h-6 text-school-blue" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Jurnal Pending --}}
            <a href="{{ route('supervisor.journal-validations.index') }}" class="block group">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface transition hover:border-amber-300 dark:hover:border-amber-500/40 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Jurnal Pending</p>
                            <h3 class="mt-2 text-3xl font-bold {{ $pendingJournals > 0 ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500' }}">{{ $pendingJournals }}</h3>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">menunggu verifikasi</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $pendingJournals > 0 ? 'bg-amber-500/10 dark:bg-amber-500/20' : 'bg-gray-100 dark:bg-white/[0.06]' }} shrink-0">
                            <svg class="w-6 h-6 {{ $pendingJournals > 0 ? 'text-amber-500' : 'text-gray-400 dark:text-gray-500' }}" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Siswa Belum Dinilai --}}
            <a href="{{ route('supervisor.assessments.index') }}" class="block group">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-amoled-border dark:bg-amoled-surface transition hover:border-red-300 dark:hover:border-red-500/40 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-amoled-text">Belum Dinilai</p>
                            <h3 class="mt-2 text-3xl font-bold {{ $studentsUnassessed > 0 ? 'text-red-500' : 'text-emerald-500' }}">{{ $studentsUnassessed }}</h3>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">siswa belum ada nilai</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $studentsUnassessed > 0 ? 'bg-red-500/10 dark:bg-red-500/20' : 'bg-emerald-500/10 dark:bg-emerald-500/20' }} shrink-0">
                            <svg class="w-6 h-6 {{ $studentsUnassessed > 0 ? 'text-red-500' : 'text-emerald-500' }}" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Daftar Siswa Bimbingan --}}
        @if($internships->count() > 0)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Daftar Siswa Bimbingan</h2>
                    <a href="{{ route('supervisor.journal-validations.index') }}" class="text-xs font-medium text-school-blue hover:underline">Kelola Jurnal →</a>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-amoled-border">
                                <th class="py-3 px-6 sm:px-8 font-medium text-gray-500 dark:text-amoled-text">Nama Siswa</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-amoled-text">Tempat PKL</th>
                                <th class="py-3 px-4 font-medium text-gray-500 dark:text-amoled-text">Jurnal</th>
                                <th class="py-3 px-6 sm:px-8 font-medium text-gray-500 dark:text-amoled-text text-right">Penilaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                            @foreach($internships as $internship)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.03] transition duration-150">
                                    <td class="py-3.5 px-6 sm:px-8">
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $internship->student->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $internship->student->class_name ?? '-' }}</p>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <p class="text-gray-700 dark:text-gray-300">{{ $internship->industry->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $internship->industry->city ?? '' }}</p>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @php
                                            $pending = $internship->dailyJournals->where('verification_status', 'pending')->count();
                                            $total   = $internship->dailyJournals->count();
                                        @endphp
                                        @if($pending > 0)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 px-2 py-0.5 text-xs font-medium text-amber-600 dark:text-amber-400">
                                                {{ $pending }} pending
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $total }} jurnal</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-6 sm:px-8 text-right">
                                        @if($internship->assessmentScores->isEmpty())
                                            <a href="{{ route('supervisor.assessments.edit', $internship->id) }}"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-500/20 transition">
                                                Belum Dinilai
                                            </a>
                                        @else
                                            <a href="{{ route('supervisor.assessments.edit', $internship->id) }}"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 px-3 py-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/20 transition">
                                                Sudah Dinilai
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-gray-100 dark:divide-amoled-border">
                    @foreach($internships as $internship)
                        @php
                            $pending = $internship->dailyJournals->where('verification_status', 'pending')->count();
                        @endphp
                        <div class="p-4 space-y-2">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 dark:text-white text-sm truncate">{{ $internship->student->user->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $internship->student->class_name ?? '-' }}</p>
                                </div>
                                @if($internship->assessmentScores->isEmpty())
                                    <a href="{{ route('supervisor.assessments.edit', $internship->id) }}"
                                       class="inline-flex items-center rounded-lg bg-red-500/10 px-2.5 py-1 text-xs font-medium text-red-500 shrink-0">
                                        Nilai
                                    </a>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-500 shrink-0">
                                        Dinilai ✓
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $internship->industry->name ?? '-' }}
                                @if($pending > 0)
                                    &bull; <span class="text-amber-500">{{ $pending }} jurnal pending</span>
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface p-10 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-school-blue/10 dark:bg-school-blue/20 mx-auto mb-4">
                    <svg class="w-8 h-8 text-school-blue" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">Belum Ada Siswa Bimbingan</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Anda belum dialokasikan ke siswa manapun untuk tahun ajaran ini.</p>
            </div>
        @endif
    </div>
@endsection
