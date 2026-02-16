@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Edit Guru Pembimbing
            </h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li>
                        <a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('supervisors.index') }}">Guru Pembimbing</a>
                    </li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Edit</li>
                </ol>
            </nav>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <form action="{{ route('supervisors.update', $supervisor->user_id) }}" method="POST">
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
                        <input type="text" id="name" name="name" value="{{ old('name', $supervisor->user->name) }}" placeholder="cth. Budi Santoso, S.Pd." required
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $supervisor->user->email) }}" placeholder="cth. nama@email.com"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Section: Data Sekolah -->
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">
                        Data Sekolah
                    </h3>
                </div>
                <div class="p-6 sm:p-8 space-y-6 border-b border-gray-200 dark:border-amoled-border">
                    <!-- NIP -->
                    <div>
                        <label for="nip" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            NIP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip', $supervisor->nip) }}" placeholder="cth. 198507012010011001" required
                               class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('nip')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Program Keahlian (Dropdown) -->
                    <div>
                        <label for="department_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Program Keahlian <span class="text-red-500">*</span>
                        </label>
                        <select id="department_id" name="department_id" required
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:focus:border-school-blue appearance-none cursor-pointer">
                            <option value="" class="dark:bg-amoled-surface">— Pilih Program Keahlian —</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" class="dark:bg-amoled-surface" {{ old('department_id', $supervisor->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Toggle Kepala Program -->
                    <div x-data="{ isDeptHead: {{ old('is_department_head', $supervisor->user->hasRole('department_head') ? '1' : '0') ? 'true' : 'false' }} }">
                        <label class="flex items-center justify-between cursor-pointer rounded-xl border border-gray-200 dark:border-amoled-border p-4 transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/10 dark:bg-amber-500/20 shrink-0">
                                    <svg class="w-4.5 h-4.5 text-amber-500" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l3.057-3L12 3.5 15.943 0 19 3l-3 6h-8L5 3zM12 12l-4 9h8l-4-9z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white">Jadikan Kepala Program Keahlian</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Guru ini akan memiliki hak akses sebagai Kaprog</p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="hidden" name="is_department_head" value="0">
                                <input type="checkbox" name="is_department_head" value="1" x-model="isDeptHead"
                                       class="sr-only peer" />
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('supervisors.index') }}"
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
