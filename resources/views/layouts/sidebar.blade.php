<aside
    class="fixed left-0 top-0 z-50 flex h-screen w-[280px] flex-col overflow-y-hidden bg-white dark:bg-amoled-surface duration-300 ease-linear lg:static lg:translate-x-0 border-r border-gray-200 dark:border-amoled-border"
    :class="{
        'w-[280px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[88px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen,
        'max-lg:-translate-x-full': !$store.sidebar.isMobileOpen,
        'max-lg:translate-x-0': $store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)"
    @click.outside="$store.sidebar.setMobileOpen(false)"
>
    <!-- Logo -->
    <div class="flex items-center justify-center gap-2 px-6 py-5 h-[72px] border-b border-gray-200 dark:border-amoled-border">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <template x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                 <div class="flex flex-col items-center">
                    <span class="text-2xl font-bold text-school-blue dark:text-white tracking-widest">SIM-PKL</span>
                    <span class="text-[10px] font-medium text-gray-400 dark:text-amoled-text uppercase tracking-wider">SMK 2 Magelang</span>
                 </div>
            </template>
            <template x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                 <span class="text-xl font-bold text-school-blue dark:text-white">SP</span>
            </template>
        </a>
    </div>

    <!-- Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mt-4 px-4 py-4 lg:mt-6 lg:px-5">
            <!-- Dashboard Group -->
            <div>
                <h3 class="mb-3 ml-3 text-[11px] font-semibold text-gray-400 dark:text-amoled-text uppercase tracking-widest"
                    x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                    MENU
                </h3>
                <ul class="mb-6 flex flex-col gap-1">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('dashboard')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="fill-current w-5 h-5 flex-shrink-0" width="20" height="20" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 0H2C0.89543 0 0 0.89543 0 2V16C0 17.1046 0.89543 18 2 18H16C17.1046 18 18 17.1046 18 16V2C18 0.89543 17.1046 0 16 0ZM7 16H2V11H7V16ZM16 16H9V11H16V16ZM7 9H2V2H7V9ZM16 9H9V2H16V9Z" fill=""/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Master Data Group -->
            <div>
                 <h3 class="mb-3 ml-3 text-[11px] font-semibold text-gray-400 dark:text-amoled-text uppercase tracking-widest"
                    x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                    MASTER DATA
                </h3>
                <ul class="mb-6 flex flex-col gap-1">
                    <!-- Departments -->
                    <li>
                        <a href="{{ route('departments.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('departments.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="fill-current w-5 h-5 flex-shrink-0" width="20" height="20" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 13H3V5H15V13ZM3 3C1.895 3 1 3.895 1 5V13C1 14.105 1.895 15 3 15H15C16.105 15 17 14.105 17 13V5C17 3.895 16.105 3 15 3H3ZM8 7H13V9H8V7ZM4 7H6V9H4V7ZM4 10H6V12H4V10ZM8 10H13V12H8V10Z" fill=""/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Kompetensi Keahlian</span>
                        </a>
                    </li>

                    <!-- Academic Years -->
                    <li>
                        <a href="{{ route('academic-years.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('academic-years.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="fill-current w-5 h-5 flex-shrink-0" width="20" height="20" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2H13V0H11V2H5V0H3V2H2C0.9 2 0 2.9 0 4V16C0 17.1 0.9 18 2 18H14C15.1 18 16 17.1 16 16V4C16 2.9 15.1 2 14 2ZM14 16H2V7H14V16ZM14 5H2V4H14V5ZM4 9H10V15H4V9Z" fill=""/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Tahun Ajaran</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>
