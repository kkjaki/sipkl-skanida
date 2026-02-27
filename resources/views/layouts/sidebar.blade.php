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
            <!-- Dashboard (All Roles) -->
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

            {{-- ============================================ --}}
            {{-- ADMIN MENU --}}
            {{-- ============================================ --}}
            @role('admin')
            <div>
                 <h3 class="mb-3 ml-3 text-[11px] font-semibold text-gray-400 dark:text-amoled-text uppercase tracking-widest"
                    x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                DATA MASTER
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
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Program Keahlian</span>
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

                    <!-- Students -->
                    <li>
                        <a href="{{ route('students.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('students.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="fill-current w-5 h-5 flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 10C9.21 10 11 8.21 11 6C11 3.79 9.21 2 7 2C4.79 2 3 3.79 3 6C3 8.21 4.79 10 7 10ZM7 4C8.1 4 9 4.9 9 6C9 7.1 8.1 8 7 8C5.9 8 5 7.1 5 6C5 4.9 5.9 4 7 4ZM7 12C4.33 12 0 13.34 0 16V18H14V16C14 13.34 9.67 12 7 12ZM2 16C2.22 15.28 4.31 14 7 14C9.7 14 11.8 15.29 12 16H2ZM15 10C16.65 10 18 8.65 18 7C18 5.35 16.65 4 15 4C14.71 4 14.43 4.04 14.17 4.11C14.67 4.94 15 5.93 15 6.93C15 7.93 14.67 8.93 14.17 9.76C14.43 9.83 14.71 9.87 15 10ZM16 18H20V16C20 14.46 17.49 13.34 15.82 13.04C16.55 13.67 17 14.67 17 16V18H16Z" fill=""/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Peserta Didik</span>
                        </a>
                    </li>

                    <!-- Supervisors -->
                    <li>
                        <a href="{{ route('supervisors.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('supervisors.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="fill-current w-5 h-5 flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 10C12.21 10 14 8.21 14 6C14 3.79 12.21 2 10 2C7.79 2 6 3.79 6 6C6 8.21 7.79 10 10 10ZM10 4C11.1 4 12 4.9 12 6C12 7.1 11.1 8 10 8C8.9 8 8 7.1 8 6C8 4.9 8.9 4 10 4ZM2 16V18H18V16C18 13.34 12.67 12 10 12C7.33 12 2 13.34 2 16ZM4 16C4.22 15.28 7.31 14 10 14C12.7 14 15.8 15.29 16 16H4Z" fill=""/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Guru Pembimbing</span>
                        </a>
                    </li>

                    <!-- Industries -->
                    <li>
                        <a href="{{ route('industries.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('industries.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-16 0H3m2 0V5m12 0v16m-5-14h.01M9 7h.01M9 11h.01M12 11h.01M9 15h.01M12 15h.01"/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Industri</span>
                        </a>
                    </li>

                    <!-- Cetak Sertifikat -->
                    <li>
                        <a href="{{ route('certificates.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('certificates.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Cetak Sertifikat</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endrole

            {{-- ============================================ --}}
            {{-- CURRICULUM MENU (WKS Kurikulum) --}}
            {{-- ============================================ --}}
            @role('curriculum')
            <div>
                 <h3 class="mb-3 ml-3 text-[11px] font-semibold text-gray-400 dark:text-amoled-text uppercase tracking-widest"
                    x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                KELOLA DATA KURIKULUM
                </h3>
                <ul class="mb-6 flex flex-col gap-1">
                    <!-- Alokasi Guru -->
                    <li>
                        <a href="{{ route('supervisors.allocate') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('supervisors.allocate.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="fill-current w-5 h-5 flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 10C12.21 10 14 8.21 14 6C14 3.79 12.21 2 10 2C7.79 2 6 3.79 6 6C6 8.21 7.79 10 10 10ZM10 4C11.1 4 12 4.9 12 6C12 7.1 11.1 8 10 8C8.9 8 8 7.1 8 6C8 4.9 8.9 4 10 4ZM2 16V18H18V16C18 13.34 12.67 12 10 12C7.33 12 2 13.34 2 16ZM4 16C4.22 15.28 7.31 14 10 14C12.7 14 15.8 15.29 16 16H4Z" fill=""/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Alokasi Guru</span>
                        </a>
                    </li>

                    <!-- Indikator Penilaian -->
                    <li>
                        <a href="{{ route('evaluation-indicators.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('evaluation-indicators.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="fill-current w-5 h-5 flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10C2 5.58 5.58 2 10 2C14.42 2 18 5.58 18 10C18 14.42 14.42 18 10 18C5.58 18 2 14.42 2 10ZM4 10C4 13.31 6.69 16 10 16C13.31 16 16 13.31 16 10C16 6.69 13.31 4 10 4C6.69 4 4 6.69 4 10ZM11 11H9V6H11V11ZM11 14H9V12H11V14Z" fill=""/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Indikator Penilaian</span>
                        </a>
                    </li>

                    <!-- Rekap Nilai -->
                    <li>
                        <a href="{{ route('grade-recap.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('grade-recap.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Rekap Nilai</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endrole

            {{-- ============================================ --}}
            {{-- STUDENT MENU --}}
            {{-- ============================================ --}}
            @role('student')
            <div>
                <h3 class="mb-3 ml-3 text-[11px] font-semibold text-gray-400 dark:text-amoled-text uppercase tracking-widest"
                    x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                    PKL
                </h3>
                <ul class="mb-6 flex flex-col gap-1">
                    <!-- Pengajuan Lokasi -->
                    <li>
                        <a href="{{ route('student.proposals.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('student.proposals.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="fill-current w-5 h-5 flex-shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4C4 2.89543 4.89543 2 6 2H10.5858C11.1162 2 11.6249 2.21071 12 2.58579L15.4142 6C15.7893 6.37507 16 6.88378 16 7.41421V16C16 17.1046 15.1046 18 14 18H6C4.89543 18 4 17.1046 4 16V4ZM14 16V8H11C10.4477 8 10 7.55228 10 7V4H6V16H14ZM12 4.41421L13.5858 6H12V4.41421ZM7 10H13V12H7V10ZM7 13H11V15H7V13Z" fill=""/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Pengajuan Lokasi</span>
                        </a>
                    </li>

                    <!-- Jurnal Harian -->
                    <li>
                        <a href="{{ route('student.journals.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('student.journals.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Jurnal Harian</span>
                        </a>
                    </li>

                    <!-- Validasi Sertifikat -->
                    <li>
                        <a href="{{ route('student.certificate-validations.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('student.certificate-validations.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Validasi Sertifikat</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endrole

            {{-- ============================================ --}}
            {{-- DEPARTMENT HEAD (KAPROG) MENU --}}
            {{-- ============================================ --}}
            @role('department_head')
            <div>
                <h3 class="mb-3 ml-3 text-[11px] font-semibold text-gray-400 dark:text-amoled-text uppercase tracking-widest"
                    x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                     PENEMPATAN
                </h3>
                <ul class="mb-6 flex flex-col gap-1">
                    <li>
                        <a href="{{ route('placements.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('placements.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Plotting Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supervisor-placements.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('supervisor-placements.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Plotting Guru</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('verification.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('verification.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Verifikasi Industri</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endrole

            {{-- ============================================ --}}
            {{-- SUPERVISOR MENU --}}
            {{-- ============================================ --}}
            @role('supervisor')
            <div>
                <h3 class="mb-3 ml-3 text-[11px] font-semibold text-gray-400 dark:text-amoled-text uppercase tracking-widest"
                    x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                    PEMBIMBINGAN
                </h3>
                <ul class="mb-6 flex flex-col gap-1">
                    <li>
                        <a href="{{ route('supervisor.journal-validations.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('supervisor.journal-validations.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Validasi Logbook</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supervisor.assessments.index') }}"
                           class="group relative flex items-center gap-2.5 rounded-xl px-4 py-2.5 font-medium text-sm duration-200 ease-in-out
                                  {{ request()->routeIs('supervisor.assessments.*')
                                      ? 'bg-school-blue/10 text-school-blue dark:bg-white/[0.08] dark:text-white'
                                      : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.06] dark:hover:text-white' }}"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>Penilaian Siswa</span>
                        </a>
                    </li>
                </ul>
            </div>
            @endrole
        </nav>
    </div>
</aside>
