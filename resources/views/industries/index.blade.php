@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Manajemen Industri
            </h2>
            <a href="{{ route('industries.create') }}"
                class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                <span>
                    <svg class="fill-current w-5 h-5" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20C15.5228 20 20 15.5228 20 10C20 4.47715 15.5228 0 10 0ZM15 11H11V15H9V11H5V9H9V5H11V9H15V11Z"
                            fill="" />
                    </svg>
                </span>
                Tambah Industri
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div
                class="flex w-full border-l-4 border-emerald-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" width="20" height="20" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div
                class="flex w-full border-l-4 border-red-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 shrink-0" width="20" height="20" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        @if (session('info'))
            <div
                class="flex w-full border-l-4 border-blue-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500 shrink-0" width="20" height="20" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('industries.index') }}"
            class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative flex-1 sm:max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-5 h-5 text-gray-400" width="20" height="20" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari nama industri atau kota..."
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
            </div>

            <div class="relative sm:min-w-[200px]">
                <select name="filter" onchange="this.form.submit()" aria-label="Filter Status"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-4 pr-10 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer">
                    <option value="" class="dark:bg-amoled-surface">Semua Status</option>
                    <option value="pending_verification" {{ ($filter ?? '') === 'pending_verification' ? 'selected' : '' }}
                        class="dark:bg-amoled-surface">Menunggu Verifikasi</option>
                    <option value="pending_quota" {{ ($filter ?? '') === 'pending_quota' ? 'selected' : '' }}
                        class="dark:bg-amoled-surface">Menunggu Kuota</option>
                    <option value="open" {{ ($filter ?? '') === 'open' ? 'selected' : '' }}
                        class="dark:bg-amoled-surface">Aktif (Terbuka)</option>
                    <option value="blacklisted" {{ ($filter ?? '') === 'blacklisted' ? 'selected' : '' }}
                        class="dark:bg-amoled-surface">Ditolak/Blacklist</option>
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </span>
            </div>

            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-school-blue py-2.5 px-5 text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 shadow-sm h-11">
                Cari
            </button>

            @if ($search || $filter)
                <a href="{{ route('industries.index') }}"
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 dark:border-amoled-border py-2.5 px-4 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 h-11">
                    <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Reset
                </a>
            @endif

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
                            <th
                                class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left w-12">
                                No</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">
                                Nama
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">
                                Kota
                            </th>
                            <th
                                class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center w-20">
                                Kuota</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">
                                Status</th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-left">
                                Sumber</th>
                            <th
                                class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-center w-28">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                        @forelse($industries as $index => $industry)
                            <tr x-data @click="window.location.href = '{{ route('industries.edit', $industry->id) }}'" class="hover:bg-gray-50 dark:hover:bg-white/[0.03] transition duration-150 cursor-pointer">
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $industries->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-4">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $industry->name }}</p>
                                    @if ($industry->contact_person)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            {{ $industry->contact_person }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $industry->city }}
                                </td>
                                <td class="py-3 px-4 text-sm text-center font-semibold text-gray-800 dark:text-white">
                                    {{ $industry->total_quota }}
                                </td>
                                <td class="py-3 px-4">
                                    @if (!$industry->is_synced && $industry->status !== 'blacklisted')
                                        <span
                                            class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-yellow-500/10 text-yellow-600 border-yellow-500/20 dark:bg-yellow-500/20 dark:text-yellow-400 dark:border-yellow-500/30">
                                            Menunggu Verifikasi Kaprog
                                        </span>
                                    @elseif($industry->status === 'blacklisted')
                                        <span
                                            class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-900/10 text-red-600 border-red-900/20 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30">
                                            Ditolak/Blacklist
                                        </span>
                                    @elseif($industry->is_synced && $industry->total_quota === 0)
                                        <span
                                            class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-cyan-500/10 text-cyan-600 border-cyan-500/20 dark:bg-cyan-500/20 dark:text-cyan-400 dark:border-cyan-500/30">
                                            Menunggu Input Kuota
                                        </span>
                                    @elseif($industry->status === 'full')
                                        <span
                                            class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-500/10 text-red-500 border-red-500/20 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30">
                                            Kuota Penuh
                                        </span>
                                    @else
                                        <span
                                            class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30">
                                            Aktif (Terbuka)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if ($industry->student_submitter_id)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-violet-500/10 text-violet-600 border-violet-500/20 dark:bg-violet-500/20 dark:text-violet-400 dark:border-violet-500/30">
                                            <svg class="w-3 h-3" width="12" height="12" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            {{ $industry->studentSubmitter->name ?? 'Siswa' }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Admin</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4" @click.stop>
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('industries.edit', $industry->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-school-blue dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-blue-400 transition duration-150"
                                            title="Edit" aria-label="Edit {{ $industry->name }}">
                                            <svg class="w-4 h-4" width="16" height="16" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        @if ($industry->is_synced)
                                            <!-- Copy Magic Link -->
                                            <div x-data="{
                                                copied: false,
                                                copyLink() {
                                                    const link = '{{ URL::temporarySignedRoute('mitra.confirm', now()->addDays(14), ['industry' => $industry->id]) }}';
                                                    navigator.clipboard.writeText(link);
                                                    this.copied = true;
                                                    setTimeout(() => this.copied = false, 2000);
                                                }
                                            }" class="relative">
                                                <button @click="copyLink" type="button"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 dark:text-gray-400 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-400 transition duration-150"
                                                    title="Copy Link Akses Mitra"
                                                    aria-label="Copy Link Akses Mitra {{ $industry->name }}">
                                                    <svg x-show="!copied" class="w-4 h-4" width="16" height="16"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                        </path>
                                                    </svg>
                                                    <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-500"
                                                        width="16" height="16" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="copied" x-cloak
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 translate-y-1"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-[10px] font-bold rounded shadow-xl whitespace-nowrap z-10">
                                                    Link Copied!
                                                </div>
                                            </div>
                                        @endif

                                        <form action="{{ route('industries.destroy', $industry->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus industri ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-500 dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400 transition duration-150"
                                                title="Hapus" aria-label="Hapus {{ $industry->name }}">
                                                <svg class="w-4 h-4" width="16" height="16" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
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
                <div x-data @click="window.location.href = '{{ route('industries.edit', $industry->id) }}'"
                    class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-amoled-border dark:bg-amoled-surface cursor-pointer hover:bg-gray-50 dark:hover:bg-white/[0.03] transition duration-150">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $industry->name }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $industry->city }}</p>
                        </div>
                        @if (!$industry->is_synced && $industry->status !== 'blacklisted')
                            <span
                                class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-yellow-500/10 text-yellow-600 border-yellow-500/20 dark:bg-yellow-500/20 dark:text-yellow-400 dark:border-yellow-500/30">Menunggu
                                Verifikasi</span>
                        @elseif($industry->status === 'blacklisted')
                            <span
                                class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-900/10 text-red-600 border-red-900/20 dark:bg-red-900/20 dark:text-red-400 dark:border-red-900/30">Ditolak/Blacklist
                            </span>
                        @elseif($industry->is_synced && $industry->total_quota === 0)
                            <span
                                class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-cyan-500/10 text-cyan-600 border-cyan-500/20 dark:bg-cyan-500/20 dark:text-cyan-400 dark:border-cyan-500/30">Menunggu
                                Input Kuota</span>
                        @elseif($industry->status === 'full')
                            <span
                                class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-500/10 text-red-500 border-red-500/20 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30">Kuota
                                Penuh</span>
                        @else
                            <span
                                class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30">Aktif
                                (Terbuka)
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mb-1">
                        <span>Kuota: <strong
                                class="text-gray-800 dark:text-white">{{ $industry->total_quota }}</strong></span>
                        @if ($industry->contact_person)
                            <span>• {{ $industry->contact_person }}</span>
                        @endif
                    </div>
                    @if ($industry->student_submitter_id)
                        <span
                            class="inline-flex items-center gap-1 rounded-lg px-2 py-0.5 text-xs font-semibold border bg-violet-500/10 text-violet-600 border-violet-500/20 dark:bg-violet-500/20 dark:text-violet-400 dark:border-violet-500/30 mb-2">
                            <svg class="w-3 h-3" width="12" height="12" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $industry->studentSubmitter->name ?? 'Siswa' }}
                        </span>
                    @endif
                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-amoled-border" @click.stop>
                        <a href="{{ route('industries.edit', $industry->id) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium text-school-blue hover:underline"
                            aria-label="Edit {{ $industry->name }}">
                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>

                        @if ($industry->is_synced)
                            <!-- Copy Magic Link (Mobile) -->
                            <div x-data="{
                                copied: false,
                                copyLink() {
                                    const link = '{{ URL::temporarySignedRoute('mitra.confirm', now()->addDays(14), ['industry' => $industry->id, 'v' => $industry->updated_at->timestamp]) }}';
                                    navigator.clipboard.writeText(link);
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                }
                            }" class="flex items-center">
                                <button @click="copyLink" type="button"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 hover:underline"
                                    aria-label="Copy Link Akses Mitra {{ $industry->name }}">
                                    <svg x-show="!copied" class="w-3.5 h-3.5" width="14" height="14"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                    <svg x-show="copied" x-cloak class="w-3.5 h-3.5" width="14" height="14"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span x-text="copied ? 'Link Copied!' : 'Copy Link Akses'"></span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('industries.destroy', $industry->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus industri ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-red-500 hover:underline"
                                aria-label="Hapus {{ $industry->name }}">
                                <svg class="w-3.5 h-3.5" width="14" height="14" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
                    <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada data industri.</p>
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
