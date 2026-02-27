<header
    class="sticky top-0 flex w-full bg-white dark:bg-amoled-surface border-b border-gray-200 dark:border-amoled-border z-30 shadow-sm"
    x-data="{
        isApplicationMenuOpen: false,
        toggleApplicationMenu() {
            this.isApplicationMenuOpen = !this.isApplicationMenuOpen;
        }
    }">
    <div class="flex flex-col items-center justify-between grow lg:flex-row lg:px-6">
        <div
            class="flex items-center justify-between w-full gap-2 px-3 py-3 border-b border-gray-200 dark:border-amoled-border sm:gap-4 lg:justify-normal lg:border-b-0 lg:px-0 lg:py-4">

            <!-- Desktop Sidebar Toggle Button -->
            <button
                class="hidden lg:flex items-center justify-center w-10 h-10 text-gray-500 border border-gray-200 rounded-xl dark:border-amoled-border dark:text-gray-400 lg:h-11 lg:w-11 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-white/[0.06] dark:hover:text-white transition-colors duration-200"
                @click.stop="$store.sidebar.toggleExpanded()" aria-label="Toggle Sidebar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <!-- Mobile Menu Toggle Button -->
            <button
                class="flex lg:hidden items-center justify-center w-10 h-10 text-gray-500 rounded-xl dark:text-gray-400 lg:h-11 lg:w-11 hover:bg-gray-100 dark:hover:bg-white/[0.06]"
                @click.stop="$store.sidebar.toggleMobileOpen()" aria-label="Toggle Mobile Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <!-- Logo (mobile only) -->
            <a href="/" class="lg:hidden flex flex-col items-center">
                <span class="text-lg font-bold text-school-blue dark:text-white tracking-widest">SIM-PKL</span>
            </a>

            <!-- Application Menu Toggle (mobile only) -->
            <button @click="toggleApplicationMenu()"
                class="flex items-center justify-center w-10 h-10 text-gray-700 rounded-xl dark:text-gray-400 dark:hover:bg-white/[0.06] lg:hidden">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 8a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4z" fill="currentColor"/>
                </svg>
            </button>
        </div>

        <!-- Right Side Actions -->
        <div x-show="isApplicationMenuOpen" x-cloak
            class="items-center justify-between w-full gap-4 px-5 py-4 flex shadow-theme-md lg:!flex lg:justify-end lg:px-0 lg:shadow-none bg-white dark:bg-amoled-surface lg:bg-transparent">

            <div class="flex items-center gap-2">
                <!-- Theme Toggle -->
                <button
                    class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-xl hover:text-gray-900 h-10 w-10 hover:bg-gray-100 dark:border-amoled-border dark:bg-amoled-surface dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white"
                    @click="$store.theme.toggle()">
                    <!-- Sun (shown in dark mode) -->
                    <svg class="hidden dark:block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill="currentColor" />
                    </svg>
                    <!-- Moon (shown in light mode) -->
                    <svg class="dark:hidden" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" fill="currentColor" />
                    </svg>
                </button>

                <!-- Notification Dropdown -->
                @include('components.header.notification-dropdown')
            </div>

            <!-- User Dropdown -->
            @include('components.header.user-dropdown')
        </div>
    </div>
</header>
