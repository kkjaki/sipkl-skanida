@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6" x-data="{
        activeTab: '{{ session('activeTab', 'info') }}',
        showModal: {{ session('openModal') ? 'true' : 'false' }}
    }">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Detail Industri</h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li><a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150"
                            href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li><a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150"
                            href="{{ route('industries.index') }}">Industri</a></li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">{{ $industry->name }}</li>
                </ol>
            </nav>
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

        <!-- MoU Status Banner -->
        @if ($industry->partnership_status === 'none')
            <div
                class="flex w-full border-l-4 border-red-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500 shrink-0" width="20" height="20" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                        <strong>Belum ada MoU aktif!</strong> Industri ini belum dapat menerima plotting siswa.
                    </p>
                </div>
            </div>
        @elseif($industry->partnership_status === 'warning')
            <div
                class="flex w-full border-l-4 border-yellow-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500 shrink-0" width="20" height="20" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">
                        <strong>MoU akan berakhir dalam {{ $industry->days_until_expired }} hari!</strong> Segera perbarui
                        kerjasama.
                    </p>
                </div>
            </div>
        @elseif($industry->partnership_status === 'active')
            <div
                class="flex w-full border-l-4 border-emerald-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" width="20" height="20" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                        MoU aktif hingga <strong>{{ $industry->active_partnership->end_date->format('d F Y') }}</strong>
                    </p>
                </div>
            </div>
        @endif

        <!-- Tabs -->
        <div
            class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface overflow-hidden">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200 dark:border-amoled-border">
                <nav class="flex -mb-px">
                    <button @click="activeTab = 'info'" type="button"
                        :class="activeTab === 'info' ? 'border-school-blue text-school-blue' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-150">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Industri
                        </span>
                    </button>
                    <button @click="activeTab = 'partnerships'" type="button"
                        :class="activeTab === 'partnerships' ? 'border-school-blue text-school-blue' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-150">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Riwayat Kerjasama (MoU)
                            @if ($industry->partnerships->count() > 0)
                                <span
                                    class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-school-blue rounded-full">{{ $industry->partnerships->count() }}</span>
                            @endif
                        </span>
                    </button>
                </nav>
            </div>

            <!-- Tab Content: Info -->
            <div x-show="activeTab === 'info'" x-transition class="p-6 sm:p-8">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Nama
                            Industri</dt>
                        <dd class="mt-0.5 text-sm font-medium text-gray-800 dark:text-white">{{ $industry->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Kota</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->city }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Alamat
                        </dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Contact
                            Person</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->contact_person ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Email</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">No.
                            Telepon</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ $industry->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Status
                        </dt>
                        <dd class="mt-0.5">
                            @if ($industry->status === 'open')
                                <span
                                    class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-emerald-500/10 text-emerald-600 border-emerald-500/20 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30">Aktif
                                    (Open)</span>
                            @elseif($industry->status === 'blacklisted')
                                <span
                                    class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-red-500/10 text-red-500 border-red-500/20 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/30">Ditolak</span>
                            @else
                                <span
                                    class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border bg-gray-500/10 text-gray-600 border-gray-500/20 dark:bg-gray-500/20 dark:text-gray-400 dark:border-gray-500/30">{{ ucfirst($industry->status) }}</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">Total
                            Kuota</dt>
                        <dd class="mt-0.5 text-sm font-semibold text-school-blue dark:text-school-blue">
                            {{ $industry->total_quota }} siswa</dd>
                    </div>
                    @if ($industry->student_submitter_id)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                                Diajukan Oleh</dt>
                            <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">
                                {{ $industry->studentSubmitter->name ?? '-' }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Tab Content: Partnerships -->
            <div x-show="activeTab === 'partnerships'" x-transition>
                <!-- Upload Button -->
                <div
                    class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Daftar MoU</h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Riwayat dokumen kerjasama dengan
                            industri ini</p>
                    </div>
                    <button @click="showModal = true" type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-school-blue py-2 px-4 text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Upload MoU Baru
                    </button>
                </div>

                <!-- Partnerships Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                        <thead
                            class="text-xs uppercase text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-amoled-border">
                            <tr>
                                <th class="px-6 py-4 font-semibold w-12">No</th>
                                <th class="px-6 py-4 font-semibold">Nomor Dokumen</th>
                                <th class="px-6 py-4 font-semibold">Periode Kerjasama</th>
                                <th class="px-6 py-4 font-semibold w-32">Status</th>
                                <th class="px-6 py-4 font-semibold">File MoU</th>
                                <th class="px-6 py-4 font-semibold">Catatan</th>
                                <th class="px-6 py-4 font-semibold w-24 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-amoled-border">
                            @forelse($industry->partnerships as $index => $partnership)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors duration-150">
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-200">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $partnership->document_number ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            {{ $partnership->start_date->format('d M Y') }} -
                                            {{ $partnership->end_date->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            ({{ $partnership->start_date->diffInDays($partnership->end_date) }} hari)
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {!! $partnership->status_badge !!}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($partnership->mou_file_path)
                                            <a href="{{ route('partnerships.download', $partnership) }}"
                                                class="inline-flex items-center gap-1.5 text-school-blue hover:text-school-blue/80 font-medium transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                Download
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                Tidak ada file
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-gray-600 dark:text-gray-400 text-xs">{{ Str::limit($partnership->agreement_notes ?? '-', 50) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <form action="{{ route('partnerships.destroy', $partnership) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus MoU ini? Tindakan ini tidak dapat dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
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
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="h-10 w-10 text-gray-300 dark:text-gray-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <p class="text-sm">Belum ada data MoU.</p>
                                            <p class="text-xs text-gray-400">Klik tombol "Upload MoU Baru" untuk
                                                menambahkan dokumen kerjasama.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Upload Modal -->
        <div x-show="showModal" x-cloak style="display: none;">
            <!-- Backdrop -->
            <div @click="showModal = false" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75 z-40"></div>

            <!-- Modal Content -->
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-end sm:items-center justify-center p-4">
                    <div @click.away="showModal = false" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative bg-white dark:bg-amoled-surface rounded-2xl shadow-xl max-w-2xl w-full border border-gray-200 dark:border-amoled-border">

                        <!-- Modal Header -->
                        <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upload MoU Baru</h3>
                                <button @click="showModal = false" type="button"
                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <form action="{{ route('industries.partnerships.store', $industry) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Error Summary (if any) -->
                            @if ($errors->any())
                                <div
                                    class="mx-6 mt-4 p-4 rounded-lg border-l-4 border-red-500 bg-red-50 dark:bg-red-500/10">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-red-800 dark:text-red-300">Terdapat
                                                kesalahan pada form:</p>
                                            <ul
                                                class="mt-1 text-xs text-red-700 dark:text-red-400 list-disc list-inside space-y-0.5">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="p-6 space-y-4">
                                <!-- Document Number -->
                                <div>
                                    <label for="document_number"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        Nomor Dokumen <span class="text-gray-400 text-xs">(Opsional)</span>
                                    </label>
                                    <input type="text" name="document_number" id="document_number"
                                        placeholder="Contoh: 001/MOU/SKANIDA/2026"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                                        value="{{ old('document_number') }}">
                                    @error('document_number')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date Range -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="start_date"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                            Tanggal Mulai <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="date" name="start_date" id="start_date" required
                                                min="2000-01-01" max="2099-12-31" onclick="this.showPicker()"
                                                class="h-11 w-full rounded-lg border border-gray-300 bg-white dark:bg-amoled-input px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:focus:border-school-blue cursor-pointer"
                                                value="{{ old('start_date') }}" onkeydown="return false;">
                                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        @error('start_date')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="end_date"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                            Tanggal Selesai <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="date" name="end_date" id="end_date" required
                                                min="2000-01-01" max="2099-12-31" onclick="this.showPicker()"
                                                class="h-11 w-full rounded-lg border border-gray-300 bg-white dark:bg-amoled-input px-4 py-2.5 text-sm text-gray-800 dark:text-white/90 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:focus:border-school-blue cursor-pointer"
                                                value="{{ old('end_date') }}" onkeydown="return false;">
                                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        @error('end_date')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- File Upload -->
                                <div>
                                    <label for="mou_file"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        File MoU <span class="text-gray-400 text-xs">(Opsional)</span>
                                    </label>
                                    <input type="file" name="mou_file" id="mou_file" accept=".pdf,.doc,.docx"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-school-blue file:text-white hover:file:bg-school-blue/90 file:cursor-pointer cursor-pointer dark:text-gray-400">
                                    <div class="mt-1.5 space-y-1">
                                        <p class="text-xs text-gray-400">Format: PDF, DOC, DOCX (Maksimal 5MB)</p>
                                        <p class="text-xs text-amber-600 dark:text-amber-400 flex items-start gap-1">
                                            <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>Tip: Scan dokumen MoU dengan resolusi 150-200 DPI (grayscale) untuk hasil
                                                optimal</span>
                                        </p>
                                    </div>
                                    @error('mou_file')
                                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Agreement Notes -->
                                <div>
                                    <label for="agreement_notes"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        Catatan Kerjasama <span class="text-gray-400 text-xs">(Opsional)</span>
                                    </label>
                                    <textarea name="agreement_notes" id="agreement_notes" rows="3"
                                        placeholder="Catatan tambahan tentang kerjasama ini..."
                                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue">{{ old('agreement_notes') }}</textarea>
                                    @error('agreement_notes')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div
                                class="border-t border-gray-200 dark:border-amoled-border py-4 px-6 flex justify-end gap-3">
                                <button @click="showModal = false" type="button"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 dark:border-amoled-border bg-transparent py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.05] transition duration-150">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-school-blue py-2 px-4 text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    Upload MoU
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @push('scripts')
        <script>
            // Date validation for MoU form
            document.addEventListener('DOMContentLoaded', function() {
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                if (startDateInput && endDateInput) {
                    // Prevent manual keyboard input
                    [startDateInput, endDateInput].forEach(input => {
                        input.addEventListener('keydown', function(e) {
                            e.preventDefault();
                            return false;
                        });

                        // Validate date on change
                        input.addEventListener('change', function() {
                            const value = this.value;
                            if (value) {
                                const year = parseInt(value.split('-')[0]);
                                if (year < 2000 || year > 2099) {
                                    this.value = '';
                                    alert('Tahun harus antara 2000-2099');
                                }
                            }
                        });
                    });

                    // Validate end date is after start date
                    endDateInput.addEventListener('change', function() {
                        const startDate = new Date(startDateInput.value);
                        const endDate = new Date(this.value);

                        if (startDateInput.value && this.value && endDate <= startDate) {
                            alert('Tanggal selesai harus lebih dari tanggal mulai');
                            this.value = '';
                        }
                    });

                    // Update min date for end_date when start_date changes
                    startDateInput.addEventListener('change', function() {
                        if (this.value) {
                            endDateInput.min = this.value;
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
