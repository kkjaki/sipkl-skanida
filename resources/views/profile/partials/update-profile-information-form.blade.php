<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
    <div class="p-5 sm:p-6">
        <div class="mb-5">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Informasi Profil</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui alamat email akun Anda.</p>
            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5 inline-block -mt-0.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Data nama, program keahlian, dan kelas tidak dapat diubah sendiri. Hubungi Admin jika terdapat kesalahan.
            </p>
        </div>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('patch')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nama</label>
                <input id="name" type="text" value="{{ $user->name }}" readonly
                       class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-100 dark:bg-white/[0.02] text-gray-500 dark:text-gray-400 p-2.5 text-sm cursor-not-allowed">
            </div>

            {{-- Jurusan & Kelas (role-based, read-only) --}}
            @if($user->hasRole('student') && $user->student)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Program Keahlian</label>
                        <input type="text" value="{{ $user->student->department->name ?? '-' }}" readonly
                               class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-100 dark:bg-white/[0.02] text-gray-500 dark:text-gray-400 p-2.5 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Kelas</label>
                        <input type="text" value="{{ $user->student->class_name ?? '-' }}" readonly
                               class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-100 dark:bg-white/[0.02] text-gray-500 dark:text-gray-400 p-2.5 text-sm cursor-not-allowed">
                    </div>
                </div>
            @elseif($user->hasRole('supervisor') && $user->supervisor)
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Program Keahlian</label>
                    <input type="text" value="{{ $user->supervisor->department->name ?? '-' }}" readonly
                           class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-100 dark:bg-white/[0.02] text-gray-500 dark:text-gray-400 p-2.5 text-sm cursor-not-allowed">
                </div>
            @endif

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                       class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Email Anda belum terverifikasi.
                            <button form="send-verification" class="underline text-sm text-school-blue hover:text-school-blue/80 font-medium transition-colors">
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                Link verifikasi baru telah dikirim ke alamat email Anda.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-xl bg-school-blue hover:bg-school-blue/90 text-white font-bold text-sm transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
