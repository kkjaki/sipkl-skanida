{{-- Confirm Modal Component --}}
{{-- Injected once in layout. --}}
{{-- JS API: $store.confirmModal.show({ title, message, confirmText, cancelText, type, onConfirm }) --}}
<div x-data x-show="$store.confirmModal.open" x-cloak
    class="fixed inset-0 z-[210] overflow-y-auto"
    @keydown.escape.window="$store.confirmModal.cancel()">

    {{-- Backdrop --}}
    <div x-show="$store.confirmModal.open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 dark:bg-black/70 backdrop-blur-sm"
        @click="$store.confirmModal.cancel()">
    </div>

    {{-- Modal --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="$store.confirmModal.open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative w-full max-w-md bg-white dark:bg-amoled-surface rounded-2xl shadow-xl border border-gray-200 dark:border-amoled-border overflow-hidden">

            <div class="p-6">
                {{-- Icon --}}
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl"
                    :class="{
                        'bg-red-100 dark:bg-red-500/15': $store.confirmModal.type === 'danger',
                        'bg-amber-100 dark:bg-amber-500/15': $store.confirmModal.type === 'warning',
                        'bg-blue-100 dark:bg-blue-500/15': $store.confirmModal.type === 'info',
                    }">

                    {{-- Danger Icon --}}
                    <template x-if="$store.confirmModal.type === 'danger'">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </template>

                    {{-- Warning Icon --}}
                    <template x-if="$store.confirmModal.type === 'warning'">
                        <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </template>

                    {{-- Info Icon --}}
                    <template x-if="$store.confirmModal.type === 'info'">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </template>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2"
                    x-text="$store.confirmModal.title"></h3>

                {{-- Message --}}
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center leading-relaxed"
                    x-text="$store.confirmModal.message"></p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 px-6 pb-6">
                <button @click="$store.confirmModal.cancel()" type="button"
                    class="flex-1 py-2.5 px-4 rounded-xl border border-gray-200 dark:border-amoled-border text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150">
                    <span x-text="$store.confirmModal.cancelText"></span>
                </button>
                <button @click="$store.confirmModal.confirm()" type="button"
                    class="flex-1 py-2.5 px-4 rounded-xl text-sm font-medium text-white transition duration-150 shadow-sm"
                    :class="{
                        'bg-red-500 hover:bg-red-600': $store.confirmModal.type === 'danger',
                        'bg-amber-500 hover:bg-amber-600': $store.confirmModal.type === 'warning',
                        'bg-school-blue hover:bg-school-blue/90': $store.confirmModal.type === 'info',
                    }">
                    <span x-text="$store.confirmModal.confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>
