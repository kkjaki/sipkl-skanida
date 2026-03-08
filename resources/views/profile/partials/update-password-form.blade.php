<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
    <div class="p-5 sm:p-6">
        <div class="mb-5">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Ubah Password</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            {{-- Current Password --}}
            <div>
                <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password Saat Ini</label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                       class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                @error('current_password', 'updatePassword')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label for="update_password_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password Baru</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                       class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                @error('password', 'updatePassword')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Konfirmasi Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                       class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-school-blue/20 focus:border-school-blue transition-colors">
                @error('password_confirmation', 'updatePassword')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
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
