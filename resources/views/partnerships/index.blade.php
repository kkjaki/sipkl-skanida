@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">Manajemen Dokumen Kerjasama (MoU)</h1>
                <p class="text-sm text-gray-500 dark:text-amoled-text mt-0.5">Kelola dokumen kerjasama seluruh industri mitra</p>
            </div>
        </div>

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('partnerships.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search -->
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama industri atau kota..."
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                />
            </div>

            <!-- Filter Status -->
            <div class="relative sm:min-w-[180px]">
                <select
                    name="filter"
                    onchange="this.form.submit()" aria-label="Filter Status"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-4 pr-10 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer"
                >
                    <option value="" class="dark:bg-amoled-surface">Semua Status ({{ $totalSynced }})</option>
                    <option value="active" {{ $filter === 'active' ? 'selected' : '' }} class="dark:bg-amoled-surface">MoU Aktif ({{ $countActive }})</option>
                    <option value="expiring" {{ $filter === 'expiring' ? 'selected' : '' }} class="dark:bg-amoled-surface">Segera Berakhir ({{ $countExpiring }})</option>
                    <option value="expired" {{ $filter === 'expired' ? 'selected' : '' }} class="dark:bg-amoled-surface">Expired ({{ $countExpired }})</option>
                    <option value="none" {{ $filter === 'none' ? 'selected' : '' }} class="dark:bg-amoled-surface">Belum Ada MoU ({{ $countNone }})</option>
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>

            <!-- Buttons -->
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-school-blue py-2.5 px-5 text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 shadow-sm h-11">
                Cari
            </button>
            @if($search || $filter)
                <a href="{{ route('partnerships.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 dark:border-amoled-border py-2.5 px-4 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 h-11">
                    <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reset
                </a>
            @endif

            <!-- Counter Badge -->
            <span class="text-xs text-gray-400 dark:text-gray-500 sm:ml-auto self-center whitespace-nowrap">
                Total: {{ $industries->total() }} industri
            </span>
        </form>

        <!-- Table (Desktop) -->
        <div
            class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface hidden sm:block">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-amoled-border">
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left w-12">No</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Industri</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Kota</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Status MoU</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Periode Aktif</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                        @forelse($industries as $index => $industry)
                            @php
                                $activePartnership = $industry->partnerships->first(fn($p) => now()->betweenIncluded($p->start_date->startOfDay(), $p->end_date->endOfDay()));
                                $upcomingPartnership = $industry->partnerships->first(fn($p) => $p->start_date->startOfDay() > now()->startOfDay());
                                $latestPartnership = $industry->partnerships->first();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.03] transition duration-150">
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $industries->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $industry->name }}</p>
                                    @if($industry->partnerships_count > 0)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $industry->partnerships_count }} dokumen MoU</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $industry->city }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($upcomingPartnership)
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-blue-500/10 text-blue-600 border-blue-500/20 dark:bg-blue-500/20 dark:text-blue-400 dark:border-blue-500/30">
                                            Akan Datang
                                        </span>
                                    @elseif($activePartnership)
                                        @if($activePartnership->is_expiring_soon)
                                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-amber-500/10 text-amber-600 border-amber-500/20 dark:bg-amber-500/20 dark:text-amber-400 dark:border-amber-500/30">
                                                {{ $activePartnership->days_until_expiry }} hari lagi
                                            </span>
                                        @else
                                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30">
                                                Aktif
                                            </span>
                                        @endif
                                    @elseif($latestPartnership)
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-500/10 text-red-500 border-red-500/20 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30">
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-gray-500/10 text-gray-500 border-gray-500/20 dark:bg-gray-500/20 dark:text-gray-400 dark:border-gray-500/30">
                                            Belum Ada MoU
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    @if($upcomingPartnership)
                                        <span class="text-blue-600 dark:text-blue-400">{{ $upcomingPartnership->start_date->format('d M Y') }} – {{ $upcomingPartnership->end_date->format('d M Y') }}</span>
                                    @elseif($activePartnership)
                                        <span>{{ $activePartnership->start_date->format('d M Y') }} – {{ $activePartnership->end_date->format('d M Y') }}</span>
                                    @elseif($latestPartnership)
                                        <span class="text-gray-400 dark:text-gray-500 line-through">{{ $latestPartnership->start_date->format('d M Y') }} – {{ $latestPartnership->end_date->format('d M Y') }}</span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('partnerships.manage', $industry->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-blue-50 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-400 transition duration-150"
                                        title="Kelola MoU">
                                        <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-400 dark:text-gray-500">
                                            @if($filter === 'none')
                                                Semua industri sudah memiliki MoU.
                                            @elseif($filter === 'expired')
                                                Tidak ada industri dengan MoU expired.
                                            @elseif($filter === 'expiring')
                                                Tidak ada MoU yang segera berakhir.
                                            @elseif($search)
                                                Tidak ditemukan industri dengan kata kunci "{{ $search }}".
                                            @else
                                                Belum ada data industri yang di-sync.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="flex flex-col gap-3 sm:hidden">
            @forelse($industries as $industry)
                @php
                    $activePartnership = $industry->partnerships->first(fn($p) => now()->betweenIncluded($p->start_date->startOfDay(), $p->end_date->endOfDay()));
                    $upcomingPartnership = $industry->partnerships->first(fn($p) => $p->start_date->startOfDay() > now()->startOfDay());
                    $latestPartnership = $industry->partnerships->first();
                @endphp
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $industry->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $industry->city }}</p>
                        </div>
                        @if($upcomingPartnership)
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-blue-500/10 text-blue-600 border-blue-500/20 dark:bg-blue-500/20 dark:text-blue-400 dark:border-blue-500/30">
                                Akan Datang
                            </span>
                        @elseif($activePartnership)
                            @if($activePartnership->is_expiring_soon)
                                <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-amber-500/10 text-amber-600 border-amber-500/20 dark:bg-amber-500/20 dark:text-amber-400 dark:border-amber-500/30">
                                    {{ $activePartnership->days_until_expiry }} hari lagi
                                </span>
                            @else
                                <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30">
                                    Aktif
                                </span>
                            @endif
                        @elseif($latestPartnership)
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-500/10 text-red-500 border-red-500/20 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30">
                                Expired
                            </span>
                        @else
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-gray-500/10 text-gray-500 border-gray-500/20 dark:bg-gray-500/20 dark:text-gray-400 dark:border-gray-500/30">
                                Belum Ada MoU
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                        @if($upcomingPartnership)
                            <span class="text-blue-600 dark:text-blue-400">{{ $upcomingPartnership->start_date->format('d M Y') }} – {{ $upcomingPartnership->end_date->format('d M Y') }}</span>
                        @elseif($activePartnership)
                            {{ $activePartnership->start_date->format('d M Y') }} – {{ $activePartnership->end_date->format('d M Y') }}
                        @elseif($latestPartnership)
                            <span class="line-through">{{ $latestPartnership->start_date->format('d M Y') }} – {{ $latestPartnership->end_date->format('d M Y') }}</span>
                        @else
                            Belum ada dokumen kerjasama
                        @endif
                        @if($industry->partnerships_count > 0)
                            <span class="text-gray-400"> • {{ $industry->partnerships_count }} MoU</span>
                        @endif
                    </div>
                    <div class="flex items-center pt-3 border-t border-gray-100 dark:border-amoled-border">
                        <a href="{{ route('partnerships.manage', $industry->id) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium text-school-blue hover:underline">
                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Kelola MoU
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                    <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada data.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($industries->hasPages())
            <div class="mt-2">
                {{ $industries->links() }}
            </div>
        @endif
    </div>
@endsection
