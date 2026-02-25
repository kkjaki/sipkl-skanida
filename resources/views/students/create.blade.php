@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Tambah Peserta Didik Baru
            </h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li>
                        <a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('students.index') }}">Peserta Didik</a>
                    </li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Tambah Peserta Didik Baru</li>
                </ol>
            </nav>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf

                <!-- Section: Data Akun -->
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">
                        Data Akun
                    </h3>
                </div>
                <div class="p-6 sm:p-8 space-y-6 border-b border-gray-200 dark:border-amoled-border">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="cth. Ahmad Fauzi"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="cth. nama@email.com"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
                        <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">
                            <svg class="w-3.5 h-3.5 inline-block mr-0.5 -mt-0.5" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Kosongkan untuk auto-generate email sekolah (<span class="font-mono">NIS@siswa.smk.sch.id</span>).
                        </p>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Password -->
                    <div class="rounded-xl bg-blue-500/5 border border-blue-500/10 px-4 py-3">
                        <p class="text-xs text-blue-500 dark:text-blue-400 font-medium flex items-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Password default adalah NIS peserta didik.
                        </p>
                    </div>
                </div>

                <!-- Section: Data Sekolah -->
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">
                        Data Sekolah
                    </h3>
                </div>
                <div class="p-6 sm:p-8 space-y-6 border-b border-gray-200 dark:border-amoled-border">
                    <!-- NIS -->
                    <div>
                        <label for="nis" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            NIS <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nis"
                            name="nis"
                            value="{{ old('nis') }}"
                            placeholder="cth. 12345678"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
                        @error('nis')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelas (Dropdown) -->
                    <div>
                        <label for="class_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="class_name"
                            name="class_name"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer"
                        >
                            <option value="" class="dark:bg-amoled-surface">— Pilih Kelas —</option>
                            @foreach($availableClasses as $class)
                                <option value="{{ $class }}" class="dark:bg-amoled-surface" {{ old('class_name') == $class ? 'selected' : '' }}>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_name')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Program Keahlian (Dropdown) -->
                    <div>
                        <label for="department_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Program Keahlian <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="department_id"
                            name="department_id"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer"
                        >
                            <option value="" class="dark:bg-amoled-surface">— Pilih Program Keahlian —</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" class="dark:bg-amoled-surface" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Section: Kontak -->
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">
                        Kontak
                    </h3>
                </div>
                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Alamat -->
                    <div>
                        <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Alamat
                        </label>
                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            placeholder="cth. Jl. Raya No. 1, Kec. Magelang Selatan"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue resize-none"
                        >{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            No. HP
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="cth. 08123456789"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('students.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-amoled-border bg-transparent py-2.5 px-6 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 ease-in-out">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                            <svg class="w-4 h-4 fill-current" width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
