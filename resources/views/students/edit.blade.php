@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Edit Peserta Didik
            </h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li>
                        <a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('students.index') }}">Peserta Didik</a>
                    </li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Edit</li>
                </ol>
            </nav>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <form action="{{ route('students.update', $student->user_id) }}" method="POST">
                @csrf
                @method('PUT')

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
                            value="{{ old('name', $student->user->name) }}"
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
                            value="{{ old('email', $student->user->email) }}"
                            placeholder="cth. nama@email.com"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reset Email Checkbox -->
                    <div x-data="{ resetEmail: false }">
                        <label class="inline-flex items-center cursor-pointer gap-2.5">
                            <input type="checkbox" name="reset_email" value="1" x-model="resetEmail"
                                   class="w-4 h-4 rounded border-gray-300 text-school-blue focus:ring-school-blue/20 dark:border-amoled-border dark:bg-amoled-input" />
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Reset email ke pola default (<span class="font-mono text-xs">NIS@siswa.smk.sch.id</span>)
                            </span>
                        </label>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            Centang jika peserta didik lupa email dan ingin dikembalikan ke email sekolah.
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
                            value="{{ old('nis', $student->nis) }}"
                            placeholder="cth. 12345678"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
                        @error('nis')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label for="class_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="class_name"
                            name="class_name"
                            value="{{ old('class_name', $student->class_name) }}"
                            placeholder="cth. XII PPLG 1"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
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
                                <option value="{{ $department->id }}" class="dark:bg-amoled-surface" {{ old('department_id', $student->department_id) == $department->id ? 'selected' : '' }}>
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
                        >{{ old('address', $student->address) }}</textarea>
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
                            value="{{ old('phone', $student->phone) }}"
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
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
