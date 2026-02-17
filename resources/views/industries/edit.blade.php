@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 min-h-[44px]">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Edit Industri</h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li><a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('industries.index') }}">Industri</a></li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Edit</li>
                </ol>
            </nav>
        </div>

        @if($industry->student_submitter_id)
            <div class="flex w-full border-l-4 border-violet-500 bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-violet-600 dark:text-violet-400 font-medium">
                        Diajukan oleh siswa: <strong>{{ $industry->studentSubmitter->name ?? '-' }}</strong>
                        &bull; Metode pengiriman: <strong>{{ $industry->delivery_method_proposal === 'school' ? 'Diantar Sekolah' : 'Antar Sendiri' }}</strong>
                    </p>
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <form action="{{ route('industries.update', $industry->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Profil Industri</h3>
                </div>
                <div class="p-6 sm:p-8 space-y-6 border-b border-gray-200 dark:border-amoled-border">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Industri <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $industry->name) }}" placeholder="cth. PT. Telkom Indonesia" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('name')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat <span class="text-red-500">*</span></label>
                        <textarea id="address" name="address" rows="3" placeholder="cth. Jl. Jendral Sudirman No. 123" required class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue resize-none">{{ old('address', $industry->address) }}</textarea>
                        @error('address')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="city" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kota <span class="text-red-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city', $industry->city) }}" placeholder="cth. Magelang" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('city')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="contact_person" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $industry->contact_person) }}" placeholder="cth. Bapak Agus" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('contact_person')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">Email <span class="text-red-500">**</span></label>
                        <p class="mb-1.5 text-xs text-gray-400 dark:text-gray-500">Wajib diisi jika No. Telepon tidak diisi.</p>
                        <input type="email" id="email" name="email" value="{{ old('email', $industry->email) }}" placeholder="cth. humas@perusahaan.com" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('email')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">No. Telepon <span class="text-red-500">**</span></label>
                        <p class="mb-1.5 text-xs text-gray-400 dark:text-gray-500">Wajib diisi jika Email tidak diisi. (<span class="text-red-500">**</span>) Minimal salah satu harus diisi.</p>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $industry->phone) }}" placeholder="cth. 08123456789 / 0293-123456" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue" />
                        @error('phone')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>

                @if($industry->is_synced && $industry->status !== 'blacklisted')
                <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">Alokasi Kuota per Jurusan</h3>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Masukkan 0 atau kosongkan jika jurusan tidak dialokasikan.</p>
                </div>
                <div class="p-6 sm:p-8 space-y-4 border-b border-gray-200 dark:border-amoled-border">
                    @if(isset($hasOngoingInternships) && $hasOngoingInternships)
                        <div class="flex w-full border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 px-4 py-3 rounded-r-xl">
                            <div class="w-full flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                <p class="text-sm text-red-600 dark:text-red-400 font-medium">
                                    Kuota tidak dapat diubah karena terdapat siswa yang sedang menjalani PKL (ongoing) di industri ini.
                                </p>
                            </div>
                        </div>
                    @endif

                    @error('quotas')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                    @foreach($departments as $dept)
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                            <label for="quota_{{ $dept->id }}" class="sm:w-1/2 text-sm font-medium text-gray-700 dark:text-gray-400">
                                {{ $dept->name }} <span class="text-xs text-gray-400">({{ $dept->code }})</span>
                            </label>
                            <input type="number" id="quota_{{ $dept->id }}" name="quotas[{{ $dept->id }}]"
                                   value="{{ old('quotas.' . $dept->id, $existingQuotas[$dept->id] ?? 0) }}" min="0"
                                   {{ (isset($hasOngoingInternships) && $hasOngoingInternships) ? 'disabled' : '' }}
                                   class="h-11 w-full sm:w-32 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue {{ (isset($hasOngoingInternships) && $hasOngoingInternships) ? 'opacity-50 cursor-not-allowed' : '' }}" />
                            @error('quotas.' . $dept->id)
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
                @else
                <div class="p-6 sm:p-8 border-b border-gray-200 dark:border-amoled-border">
                    <div class="flex w-full border-l-4 border-amber-500 bg-amber-50 dark:bg-amber-500/10 px-4 py-3 rounded-r-xl">
                        <div class="w-full flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.27 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                            <p class="text-sm text-amber-600 dark:text-amber-400 font-medium">
                                @if($industry->status === 'blacklisted')
                                    Industri ini ditolak/blacklist. Kuota tidak dapat dialokasikan.
                                @else
                                    Industri ini belum diverifikasi oleh Kaprog. Kuota hanya dapat dialokasikan setelah verifikasi kurikulum.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('industries.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-amoled-border bg-transparent py-2.5 px-6 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 ease-in-out">Batal</a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                            <svg class="w-4 h-4 fill-current" width="16" height="16" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
