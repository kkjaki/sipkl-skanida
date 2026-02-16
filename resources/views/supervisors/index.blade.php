@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Manajemen Guru Pembimbing
            </h2>
            <a href="{{ route('supervisors.create') }}" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                <span>
                    <svg class="fill-current w-5 h-5" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20C15.5228 20 20 15.5228 20 10C20 4.47715 15.5228 0 10 0ZM15 11H11V15H9V11H5V9H9V5H11V9H15V11Z" fill=""/>
                    </svg>
                </span>
                Tambah Guru Pembimbing
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

        <!-- Search -->
        <form method="GET" action="{{ route('supervisors.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2">
                    <svg class="w-4 h-4 text-gray-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NIP..."
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
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">NIP</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Program Keahlian</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Jabatan</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">Email</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                        @forelse($supervisors as $index => $supervisor)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.03] transition duration-150">
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $supervisors->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-white">
                                    {{ $supervisor->user->name }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300 font-mono">
                                    {{ $supervisor->nip }}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $supervisor->department->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($supervisor->user->hasRole('department_head'))
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-amber-500/10 text-amber-600 border-amber-500/20 dark:bg-amber-500/20 dark:text-amber-400 dark:border-amber-500/30">
                                            Kaprog
                                        </span>
                                    @else
                                        <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-school-blue/10 text-school-blue border-school-blue/20 dark:bg-school-blue/20 dark:text-blue-400 dark:border-blue-500/30">
                                            Pembimbing
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $supervisor->user->email }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('supervisors.edit', $supervisor->user_id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-school-blue dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-blue-400 transition duration-150" title="Edit">
                                            <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('supervisors.destroy', $supervisor->user_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru pembimbing ini?')">
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
                                    Belum ada data guru pembimbing.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="flex flex-col gap-3 sm:hidden">
            @forelse($supervisors as $supervisor)
                <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $supervisor->user->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 font-mono mt-0.5">NIP: {{ $supervisor->nip }}</p>
                        </div>
                        @if($supervisor->user->hasRole('department_head'))
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-amber-500/10 text-amber-600 border-amber-500/20 dark:bg-amber-500/20 dark:text-amber-400 dark:border-amber-500/30">
                                Kaprog
                            </span>
                        @else
                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-school-blue/10 text-school-blue border-school-blue/20 dark:bg-school-blue/20 dark:text-blue-400 dark:border-blue-500/30">
                                Pembimbing
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $supervisor->department->name ?? '-' }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $supervisor->user->email }}</p>
                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-amoled-border">
                        <a href="{{ route('supervisors.edit', $supervisor->user_id) }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-school-blue hover:underline">
                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('supervisors.destroy', $supervisor->user_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru pembimbing ini?')">
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
                    <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada data guru pembimbing.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($supervisors->hasPages())
            <div class="mt-2">
                {{ $supervisors->links() }}
            </div>
        @endif
    </div>
@endsection
