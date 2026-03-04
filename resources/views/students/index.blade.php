@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Manajemen Peserta Didik
            </h2>
            <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                <span>
                    <svg class="fill-current w-4 h-4" width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20C15.5228 20 20 15.5228 20 10C20 4.47715 15.5228 0 10 0ZM15 11H11V15H9V11H5V9H9V5H11V9H15V11Z" fill=""/>
                    </svg>
                </span>
                Tambah Peserta Didik
            </a>
        </div>

        @if(session('success'))
            <div class="flex w-full border-l-4 border-success bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full">
                    <p class="text-sm text-success font-medium">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('students.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search -->
            <div class="relative flex-1 sm:max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari nama atau NIS..."
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                />
            </div>

            <!-- Filter Jurusan -->
            <div class="relative sm:min-w-[180px]">
                <select
                    name="department"
                    onchange="this.form.submit()"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-4 pr-10 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer"
                >
                    <option value="" class="dark:bg-amoled-surface">Semua Jurusan</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}" {{ ($filterDept ?? '') === $dept->name ? 'selected' : '' }} class="dark:bg-amoled-surface">{{ $dept->name }}</option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>

            <!-- Filter Kelas -->
            <div class="relative sm:min-w-[160px]">
                <select
                    name="class"
                    onchange="this.form.submit()"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-white pl-4 pr-10 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-surface dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer"
                >
                    <option value="" class="dark:bg-amoled-surface">Semua Kelas</option>
                    @foreach($availableClasses as $class)
                        <option value="{{ $class }}" {{ ($filterClass ?? '') === $class ? 'selected' : '' }} class="dark:bg-amoled-surface">{{ $class }}</option>
                    @endforeach
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>

            <!-- Buttons -->
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-school-blue py-2.5 px-5 text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 shadow-sm h-11">
                Cari
            </button>
            @if($search || $filterDept || $filterClass)
                <a href="{{ route('students.index') }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 dark:border-amoled-border py-2.5 px-4 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 h-11">
                    <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reset
                </a>
            @endif

            <!-- Counter Badge -->
            <span class="text-xs text-gray-400 dark:text-gray-500 sm:ml-auto self-center whitespace-nowrap">
                Total: {{ $students->total() }} siswa
            </span>
        </form>

        <!-- Content Container -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">

            <!-- Table View (Desktop) -->
            <div class="hidden md:block max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-white/[0.04] border-b border-gray-200 dark:border-amoled-border">
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text xl:pl-8 w-16">
                                No
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                Nama
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                NIS
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                Kelas
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                Program Keahlian
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                Email
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-right xl:pr-8">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $index => $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.04] transition duration-150 border-b border-gray-200 dark:border-amoled-border last:border-b-0">
                                <td class="py-4 px-4 xl:pl-8">
                                    <span class="text-sm text-gray-500 dark:text-amoled-text">{{ $students->firstItem() + $index }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <h5 class="font-medium text-gray-800 dark:text-white text-sm">{{ $student->user->name }}</h5>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300 font-mono">{{ $student->nis }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $student->class_name }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $deptCode = $student->department->code ?? '';
                                        $badgeColors = match(strtoupper($deptCode)) {
                                            'PPLG' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                            'PM'  => 'bg-red-500/10 text-red-500 border-red-500/20',
                                            'AKL'  => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                            'MPLB' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                            default => 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-white/[0.06] dark:text-gray-300 dark:border-amoled-border',
                                        };
                                    @endphp
                                    <span class="inline-block rounded-lg px-2.5 py-0.5 text-xs font-semibold border {{ $badgeColors }}">
                                        {{ $student->department->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm text-gray-500 dark:text-amoled-text">{{ $student->user->email }}</span>
                                </td>
                                <td class="py-4 px-4 pr-8 xl:pr-8 text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('students.edit', $student->user_id) }}" class="text-gray-400 hover:text-school-blue transition duration-150 flex items-center" title="Edit">
                                            <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('students.destroy', $student->user_id) }}" method="POST" class="inline-flex items-center" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta didik ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-danger transition duration-150 flex items-center" title="Hapus">
                                                <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 px-4 text-center text-sm text-gray-500 dark:text-amoled-text">
                                    @if($search)
                                        Tidak ditemukan peserta didik dengan kata kunci "{{ $search }}".
                                    @else
                                        Belum ada data peserta didik.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Card View (Mobile) -->
            <div class="md:hidden flex flex-col divide-y divide-gray-200 dark:divide-amoled-border">
                 @forelse ($students as $index => $student)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h5 class="font-semibold text-gray-800 dark:text-white text-sm">{{ $student->user->name }}</h5>
                                <p class="text-xs text-gray-500 dark:text-amoled-text mt-0.5 font-mono">{{ $student->nis }}</p>
                            </div>
                            @php
                                $deptCode = $student->department->code ?? '';
                                $badgeColors = match(strtoupper($deptCode)) {
                                    'PPLG' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'PM'  => 'bg-red-500/10 text-red-500 border-red-500/20',
                                    'AKL'  => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'MPLB' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200 dark:bg-white/[0.06] dark:text-gray-300 dark:border-amoled-border',
                                };
                            @endphp
                            <span class="text-xs px-2.5 py-0.5 rounded-lg border font-semibold ml-2 whitespace-nowrap {{ $badgeColors }}">{{ $student->department->code ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-amoled-text mb-1">
                            <span>{{ $student->class_name }}</span>
                            <span>{{ $student->user->email }}</span>
                        </div>
                        @if($student->address)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-3 line-clamp-1">
                                <svg class="w-3 h-3 inline -mt-0.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $student->address }}
                            </p>
                        @else
                            <div class="mb-3"></div>
                        @endif
                        <div class="flex items-center justify-end gap-4 mt-2">
                             <a href="{{ route('students.edit', $student->user_id) }}" class="text-sm font-medium text-school-blue hover:text-school-blue/80 flex items-center gap-1">
                                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>
                            <form action="{{ route('students.destroy', $student->user_id) }}" method="POST" class="inline-flex items-center" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta didik ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-danger hover:text-danger/80 flex items-center gap-1">
                                    <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                 @empty
                    <div class="p-6 text-center text-sm text-gray-500 dark:text-amoled-text">
                        @if($search)
                            Tidak ditemukan peserta didik dengan kata kunci "{{ $search }}".
                        @else
                            Belum ada data peserta didik.
                        @endif
                    </div>
                 @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($students->hasPages())
            <div class="mt-2">
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection
