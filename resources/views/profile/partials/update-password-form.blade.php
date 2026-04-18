<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
    <div class="p-5 sm:p-6">
        <div class="mb-5">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Ubah Password</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pastikan akun Anda menggunakan password yang panjang
                dan acak agar tetap aman.</p>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="space-y-5"
            x-ref="pwdForm"
            x-data="{ currentPwd: '', newPwd: '', confirmPwd: '', showConfirmModal: false }"
            @submit.prevent="showConfirmModal = true">
            @csrf
            @method('put')

            {{-- Current Password --}}
            <div>
                <label for="update_password_current_password"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password Saat Ini</label>
                <div x-data="{ showPwd: false }" class="relative">
                    <input id="update_password_current_password" name="current_password" :type="showPwd ? 'text' : 'password'"
                        x-model="currentPwd" autocomplete="current-password"
                        class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 pr-11 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                    <button type="button" @click="showPwd = !showPwd"
                        class="absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-600 dark:hover:text-gray-400 transition-colors focus:outline-none"
                        :title="showPwd ? 'Sembunyikan sandi' : 'Tampilkan sandi'">
                        <svg x-show="!showPwd" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPwd" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('current_password', 'updatePassword')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label for="update_password_password"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password Baru</label>
                <div x-data="{ showPwd: false }" class="relative">
                    <input id="update_password_password" name="password" :type="showPwd ? 'text' : 'password'"
                        x-model="newPwd" autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 pr-11 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                    <button type="button" @click="showPwd = !showPwd"
                        class="absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-600 dark:hover:text-gray-400 transition-colors focus:outline-none"
                        :title="showPwd ? 'Sembunyikan sandi' : 'Tampilkan sandi'">
                        <svg x-show="!showPwd" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPwd" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="update_password_password_confirmation"
                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Konfirmasi
                    Password</label>
                <div x-data="{ showPwd: false }" class="relative">
                    <input id="update_password_password_confirmation" name="password_confirmation" :type="showPwd ? 'text' : 'password'"
                        x-model="confirmPwd" autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 pr-11 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                    <button type="button" @click="showPwd = !showPwd"
                        class="absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-600 dark:hover:text-gray-400 transition-colors focus:outline-none"
                        :title="showPwd ? 'Sembunyikan sandi' : 'Tampilkan sandi'">
                        <svg x-show="!showPwd" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPwd" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                @error('password', 'updatePassword')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                    :disabled="!currentPwd.trim() || !newPwd.trim() || !confirmPwd.trim()"
                    :class="(!currentPwd.trim() || !newPwd.trim() || !confirmPwd.trim())
                        ? 'bg-gray-300 dark:bg-gray-700 cursor-not-allowed text-gray-500 dark:text-gray-400'
                        : 'bg-school-blue hover:bg-school-blue/90 text-white shadow-sm'"
                    class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-xl font-bold text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                </button>
            </div>

            {{-- Modal Konfirmasi sebelum submit --}}
            <div x-show="showConfirmModal" x-cloak x-transition.opacity.duration.200ms
                class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-sm">
                <div x-show="showConfirmModal" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="w-full max-w-md rounded-2xl bg-white dark:bg-amoled-surface shadow-xl border border-gray-200 dark:border-amoled-border p-6">

                    {{-- Icon --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-500/10">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Konfirmasi Ubah Password</h3>
                    </div>

                    {{-- Message --}}
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-2">
                        Setelah password diubah, Anda akan <strong class="text-gray-800 dark:text-white">otomatis keluar dari semua perangkat</strong>
                        dan harus login ulang.
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                        <strong class="text-gray-800 dark:text-white">Pastikan Anda sudah mengingat atau mencatat password baru Anda</strong>
                        sebelum melanjutkan.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col gap-2.5">
                        <button type="button" @click="$refs.pwdForm.submit()"
                            class="w-full rounded-xl bg-school-blue hover:bg-school-blue/90 py-2.5 text-sm font-bold text-white transition-colors shadow-sm">
                            Ya, Ubah Password
                        </button>
                        <button type="button" @click="showConfirmModal = false"
                            class="w-full rounded-xl bg-gray-100 dark:bg-white/[0.08] py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/[0.12] transition-colors">
                            Batal, Kembali
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
