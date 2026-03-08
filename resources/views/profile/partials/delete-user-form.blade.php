<div x-data="{ showDeleteModal: false }" class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
    <div class="p-5 sm:p-6">
        <div class="mb-5">
            <h2 class="text-base font-bold text-red-600 dark:text-red-400">Hapus Akun</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Setelah akun dihapus, semua data akan hilang secara permanen. Pastikan Anda sudah mengunduh data yang diperlukan.</p>
        </div>

        <button type="button" @click="showDeleteModal = true"
                class="inline-flex items-center justify-center gap-2 h-10 px-5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-sm transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Hapus Akun
        </button>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" class="fixed inset-0 z-[100]"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-black/70" @click="showDeleteModal = false"></div>

            <div class="relative w-full sm:max-w-md bg-white dark:bg-amoled-surface rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-amoled-border"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                 x-transition:enter-end="translate-y-0 scale-100 opacity-100">

                <form method="post" action="{{ route('profile.destroy') }}" class="p-5 sm:p-6">
                    @csrf
                    @method('delete')

                    <div class="text-center mb-5">
                        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-500/15 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Hapus Akun Anda?</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Semua data akan dihapus secara permanen. Masukkan password Anda untuk mengonfirmasi.</p>
                    </div>

                    <div class="mb-5">
                        <label for="delete_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password</label>
                        <input id="delete_password" name="password" type="password" placeholder="Masukkan password Anda"
                               class="w-full rounded-xl border border-gray-200 dark:border-amoled-border bg-gray-50 dark:bg-white/[0.03] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 p-2.5 text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-colors">
                        @error('password', 'userDeletion')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="showDeleteModal = false"
                                class="h-10 rounded-xl bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-300 font-bold text-sm hover:bg-gray-300 dark:hover:bg-white/20 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="h-10 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-sm transition-colors">
                            Ya, Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
