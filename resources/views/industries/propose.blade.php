@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Pengajuan Lokasi PKL</h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li><a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Pengajuan Lokasi PKL</li>
                </ol>
            </nav>
        </div>

        @if(session('success'))
            <div class="flex w-full border-l-4 border-emerald-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Info Banner -->
        <div class="rounded-xl bg-blue-500/5 border border-blue-500/10 px-4 py-3">
            <p class="text-xs text-blue-500 dark:text-blue-400 font-medium flex items-center gap-1.5">
                <svg class="w-4 h-4 shrink-0" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Ajukan lokasi PKL baru yang belum terdaftar di sistem. Data akan diverifikasi oleh admin sebelum disetujui.
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <form action="{{ route('student.proposals.store') }}" method="POST">
                @csrf

                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Data Perusahaan / Industri</h3>
                </div>
                <div class="p-6 sm:p-8 space-y-6 border-b border-gray-200 dark:border-amoled-border">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="cth. CV. Maju Jaya" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat <span class="text-red-500">*</span></label>
                        <textarea id="address" name="address" rows="3" placeholder="cth. Jl. Raya Magelang No. 45" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue resize-none">{{ old('address') }}</textarea>
                        @error('address')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="city" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kota <span class="text-red-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="cth. Magelang" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('city')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="contact_person" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" placeholder="cth. Bapak/Ibu Humas" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('contact_person')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">Email <span class="text-red-500">**</span></label>
                        <p class="mb-1.5 text-xs text-gray-400 dark:text-gray-500">Wajib diisi jika No. Telepon / WA tidak diisi.</p>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="cth. humas@perusahaan.com" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">No. Telepon / WA <span class="text-red-500">**</span></label>
                        <p class="mb-1.5 text-xs text-gray-400 dark:text-gray-500">Wajib diisi jika Email tidak diisi. (<span class="text-red-500">**</span>) Minimal salah satu harus diisi agar sekolah dapat menghubungi industri.</p>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="cth. 08123456789 / 0293-123456" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('phone')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-amoled-border bg-transparent py-2.5 px-6 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 ease-in-out">Batal</a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                            <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            Kirim Pengajuan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
