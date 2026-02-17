@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Manajemen Industri
            </h2>
            <a href="{{ route('industries.create') }}" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                <span>
                    <svg class="fill-current w-5 h-5" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20C15.5228 20 20 15.5228 20 10C20 4.47715 15.5228 0 10 0ZM15 11H11V15H9V11H5V9H9V5H11V9H15V11Z" fill=""/>
                    </svg>
                </span>
                Tambah Industri
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="flex w-full border-l-4 border-emerald-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="flex w-full border-l-4 border-red-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        @if(session('info'))
            <div class="flex w-full border-l-4 border-blue-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="flex items-center gap-1 border-b border-gray-200 dark:border-amoled-border">
            <a href="{{ route('industries.index', array_merge(request()->only('search'), ['filter' => ''])) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 transition duration-150
                      {{ !$filter ? 'border-school-blue text-school-blue dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                Semua
            </a>
            <a href="{{ route('industries.index', array_merge(request()->only('search'), ['filter' => 'proposal'])) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium border-b-2 transition duration-150
                      {{ $filter === 'proposal' ? 'border-school-blue text-school-blue dark:text-white' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                </svg>
                Pengajuan Siswa
            </a>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('industries.index') }}" class="flex items-center gap-3">
            @if($filter)
                <input type="hidden" name="filter" value="{{ $filter }}">
            @endif
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2">
                    <svg class="w-4 h-4 text-gray-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama industri atau kota..."
                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
            </div>
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-school-blue py-2.5 px-5 text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 shadow-sm">
                Cari
            </button>
        </form>

        <!-- Table (Desktop) -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface hidden sm:block">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-amoled-border">
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left w-12">No</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Nama</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Kota</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center w-20">Kuota</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Status</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Sumber</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                        @forelse($industries as $index => $industry)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.03] transition duration-150">
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $industries->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $industry->name }}</p>
                                    @if($industry->contact_person)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $industry->contact_person }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $industry->city }}
                                </td>
                                <td class="py-3 px-4 text-sm text-center font-semibold text-gray-800 dark:text-white">
                                    {{ $industry->total_quota }}
                                </td>
                                <td class="py-3 px-4">
                                    @if(!$industry->is_synced && $industry->status !== 'blacklisted')
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-yellow-500/10 text-yellow-600 border-yellow-500/20 dark:bg-yellow-500/20 dark:text-yellow-400 dark:border-yellow-500/30">
                                            Menunggu Verifikasi Kaprog
                                        </span>
                                    @elseif($industry->status === 'blacklisted')
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-900/10 text-red-600 border-red-900/20 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30">
                                            Ditolak / Blacklist
                                        </span>
                                    @elseif($industry->is_synced && $industry->total_quota === 0)
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-cyan-500/10 text-cyan-600 border-cyan-500/20 dark:bg-cyan-500/20 dark:text-cyan-400 dark:border-cyan-500/30">
                                            Menunggu Input Kuota
                                        </span>
                                    @elseif($industry->status === 'full')
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-500/10 text-red-500 border-red-500/20 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30">
                                            Kuota Penuh
                                        </span>
                                    @else
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30">
                                            Aktif (Open)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($industry->student_submitter_id)
                                        <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-violet-500/10 text-violet-600 border-violet-500/20 dark:bg-violet-500/20 dark:text-violet-400 dark:border-violet-500/30">
                                            <svg class="w-3 h-3" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            {{ $industry->studentSubmitter->name ?? 'Siswa' }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Admin</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($industry->is_synced && $industry->total_quota === 0)
                                            <a href="{{ route('industries.allocate', $industry->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-cyan-50 hover:text-cyan-600 dark:text-gray-400 dark:hover:bg-cyan-500/10 dark:hover:text-cyan-400 transition duration-150" title="Input Kuota">
                                                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('industries.edit', $industry->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-school-blue dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-blue-400 transition duration-150" title="Edit">
                                            <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('industries.destroy', $industry->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus industri ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-500 dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400 transition duration-150" title="Hapus">
                                                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                    Belum ada data industri.
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
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $industry->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $industry->city }}</p>
                        </div>
                        @if(!$industry->is_synced && $industry->status !== 'blacklisted')
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-yellow-500/10 text-yellow-600 border-yellow-500/20 dark:bg-yellow-500/20 dark:text-yellow-400 dark:border-yellow-500/30">Menunggu Verifikasi</span>
                        @elseif($industry->status === 'blacklisted')
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-900/10 text-red-600 border-red-900/20 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30">Ditolak / Blacklist</span>
                        @elseif($industry->is_synced && $industry->total_quota === 0)
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-cyan-500/10 text-cyan-600 border-cyan-500/20 dark:bg-cyan-500/20 dark:text-cyan-400 dark:border-cyan-500/30">Menunggu Input Kuota</span>
                        @elseif($industry->status === 'full')
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-500/10 text-red-500 border-red-500/20 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30">Kuota Penuh</span>
                        @else
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30">Aktif (Open)</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mb-1">
                        <span>Kuota: <strong class="text-gray-800 dark:text-white">{{ $industry->total_quota }}</strong></span>
                        @if($industry->contact_person)
                            <span>• {{ $industry->contact_person }}</span>
                        @endif
                    </div>
                    @if($industry->student_submitter_id)
                        <span class="inline-flex items-center gap-1 rounded-lg px-2 py-0.5 text-xs font-semibold border bg-violet-500/10 text-violet-600 border-violet-500/20 dark:bg-violet-500/20 dark:text-violet-400 dark:border-violet-500/30 mb-2">
                            <svg class="w-3 h-3" width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ $industry->studentSubmitter->name ?? 'Siswa' }}
                        </span>
                    @endif
                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-amoled-border">
                        @if($industry->is_synced && $industry->total_quota === 0)
                            <a href="{{ route('industries.allocate', $industry->id) }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-cyan-600 hover:underline">
                                <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Input Kuota
                            </a>
                        @endif
                        <a href="{{ route('industries.edit', $industry->id) }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-school-blue hover:underline">
                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('industries.destroy', $industry->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus industri ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:underline">
                                <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                    <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada data industri.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($industries->hasPages())
            <div class="mt-2">
                {{ $industries->links() }}
            </div>
        @endif
    </div>
@endsection
