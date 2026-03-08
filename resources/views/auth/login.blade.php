<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk – SIPKL SMKN 2 Magelang</title>
    <meta name="description" content="Masuk ke Sistem Informasi Manajemen PKL SMKN 2 Magelang">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    {{-- Sinkronisasi tema sebelum paint (mencegah FOUC) --}}
    <script>
        (function () {
            var saved = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = saved ? saved : (prefersDark ? 'dark' : 'light');
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        /* Hide browser native password reveal icon (Edge/Chrome) */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-webkit-credentials-auto-fill-button { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-amoled min-h-screen">

{{-- Route /login dilindungi middleware `guest` → auto redirect jika sudah login --}}

<div x-data="{ showForgotModal: false }" class="relative flex min-h-screen w-full flex-col lg:flex-row">

    {{-- ===================== KIRI: FORM ===================== --}}
    <div class="flex flex-1 flex-col bg-white dark:bg-amoled">
        <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center px-6 py-12">

            {{-- Logo + Judul (hanya tampil di mobile, karena desktop ada di panel kanan) --}}
            <div class="mb-8 flex flex-col items-center text-center lg:items-start lg:text-left">

                {{-- ================================================
                     PLACEHOLDER LOGO SEKOLAH
                     Letakkan file logo di: public/images/logo-smkn2.png
                     ================================================ --}}
                <div class="mb-4 flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl
                            bg-gray-100 dark:bg-amoled-surface border border-gray-200 dark:border-amoled-border">
                    <img
                        src="{{ asset('images/logo-smkn2.png') }}"
                        alt="Logo SMKN 2 Magelang"
                        width="64" height="64"
                        class="h-full w-full object-contain p-1.5"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                    >
                    {{-- Fallback SVG jika gambar belum ada --}}
                    <svg xmlns="http://www.w3.org/2000/svg" style="display:none" class="h-8 w-8 text-school-blue" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                    Selamat Datang!👋
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-amoled-text">
                    Masuk ke akun Anda untuk melanjutkan.
                </p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700
                            dark:border-green-800/40 dark:bg-green-500/10 dark:text-green-400">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="space-y-5">

                    {{-- Email --}}
                    <div>
                        <label for="email"
                               class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@smkn2magelang.sch.id"
                            required
                            autofocus
                            autocomplete="username"
                            class="h-11 w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition duration-150
                                   bg-white text-gray-800 placeholder:text-gray-400
                                   dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30
                                   focus:ring-3 focus:ring-school-blue/10 focus:border-school-blue
                                   @error('email')
                                       border-red-500 focus:border-red-500 focus:ring-red-200 dark:border-red-500
                                   @else
                                       border-gray-200 dark:border-amoled-border dark:focus:border-school-blue
                                   @enderror"
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password"
                               class="mb-1.5 block text-sm font-semibold text-gray-900 dark:text-white">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <div x-data="{ showPwd: false }" class="relative">
                            <input
                                :type="showPwd ? 'text' : 'password'"
                                id="password"
                                name="password"
                                placeholder="Masukkan kata sandi"
                                required
                                autocomplete="current-password"
                                class="h-11 w-full rounded-xl border py-2.5 pr-11 pl-4 text-sm outline-none transition duration-150
                                       bg-white text-gray-800 placeholder:text-gray-400
                                       dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30
                                       focus:ring-3 focus:ring-school-blue/10 focus:border-school-blue
                                       @error('password')
                                           border-red-500 focus:border-red-500 focus:ring-red-200 dark:border-red-500
                                       @else
                                           border-gray-200 dark:border-amoled-border dark:focus:border-school-blue
                                       @enderror"
                            >
                            {{-- Toggle show/hide password --}}
                            <button type="button"
                                    @click="showPwd = !showPwd"
                                    class="absolute top-1/2 right-3.5 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-600 dark:hover:text-gray-400 transition-colors focus:outline-none"
                                    :title="showPwd ? 'Sembunyikan sandi' : 'Tampilkan sandi'">
                                <svg x-show="!showPwd" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPwd" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me + Lupa Sandi --}}
                    <div class="flex items-center justify-between">
                        {{-- Custom checkbox --}}
                        <div x-data="{ checked: false }">
                            <label for="remember_me"
                                   class="flex cursor-pointer select-none items-center gap-2.5 text-sm text-gray-700 dark:text-gray-400">
                                <div class="relative">
                                    <input type="checkbox" id="remember_me" name="remember" class="sr-only"
                                           @change="checked = !checked">
                                    <div :class="checked
                                            ? 'border-school-blue bg-school-blue'
                                            : 'border-gray-300 bg-white dark:border-amoled-border dark:bg-amoled-input'"
                                         class="flex h-5 w-5 items-center justify-center rounded-md border-[1.5px] transition-colors">
                                        <span :class="checked ? 'opacity-100' : 'opacity-0'" class="transition-opacity">
                                            <svg width="12" height="12" viewBox="0 0 14 14" fill="none">
                                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                Ingat saya
                            </label>
                        </div>

                        {{-- Lupa sandi → buka modal --}}
                        <button type="button"
                                @click="showForgotModal = true"
                                class="text-sm text-school-blue hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                            Lupa kata sandi?
                        </button>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="mt-1 flex w-full items-center justify-center gap-2 rounded-2xl
                                   bg-school-blue px-4 py-3 text-sm font-bold text-white
                                   shadow-lg shadow-school-blue/10 transition-all
                                   hover:bg-school-blue/90 active:scale-[0.98] focus:outline-none
                                   focus:ring-2 focus:ring-school-blue focus:ring-offset-2 focus:ring-offset-white
                                   dark:focus:ring-offset-amoled">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk ke SIPKL
                    </button>

                </div>
            </form>

            {{-- Footer --}}
            <p class="mt-8 text-center text-xs text-gray-400 dark:text-gray-600 lg:text-left">
                © {{ date('Y') }} SMKN 2 Magelang · Sistem Informasi Manajemen PKL
            </p>

        </div>
    </div>

    {{-- ===================== KANAN: PANEL BRANDING (desktop only) ===================== --}}
    <div class="relative hidden lg:flex flex-1 items-center justify-center bg-gray-100 dark:bg-amoled-surface overflow-hidden">

        {{-- ================================================
             PLACEHOLDER FOTO SEKOLAH
             Letakkan file gambar di: public/images/school-building.jpg
             Gambar akan ditampilkan sebagai background panel kanan.
             ================================================ --}}
        <img
            src="{{ asset('images/school-building.jpg') }}"
            alt="Foto SMKN 2 Magelang"
            class="absolute inset-0 w-full h-full object-cover opacity-20 dark:opacity-10"
            onerror="this.style.display='none'"
        >

        {{-- Konten di atas gambar --}}
        <div class="relative z-10 flex flex-col items-center text-center px-8 max-w-sm">

            {{-- Logo besar --}}
            <div class="mb-6 flex h-24 w-24 items-center justify-center overflow-hidden rounded-3xl
                        bg-white dark:bg-amoled border border-gray-200 dark:border-amoled-border shadow-xl">
                <img
                    src="{{ asset('images/logo-smkn2.png') }}"
                    alt="Logo SMKN 2 Magelang"
                    width="96" height="96"
                    class="h-full w-full object-contain p-2"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                >
                {{-- Fallback SVG --}}
                <svg xmlns="http://www.w3.org/2000/svg" style="display:none" class="h-12 w-12 text-school-blue" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                </svg>
            </div>

            <h2 class="mb-1 text-2xl font-bold text-gray-900 dark:text-white">SIM-PKL</h2>
            <p class="mb-3 text-sm font-semibold text-school-blue">SMKN 2 Magelang</p>
            <p class="text-sm leading-relaxed text-gray-500 dark:text-amoled-text">
                Sistem Informasi Manajemen<br>Praktik Kerja Lapangan
            </p>
        </div>
    </div>

    {{-- ===================== MODAL: LUPA KATA SANDI ===================== --}}
    <div
        x-show="showForgotModal"
        x-cloak
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-sm"
        @click.self="showForgotModal = false"
        @keydown.escape.window="showForgotModal = false"
    >
        <div
            x-show="showForgotModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-md rounded-2xl bg-white dark:bg-amoled-surface shadow-xl border border-gray-200 dark:border-amoled-border p-6"
        >
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-500/10">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Lupa Kata Sandi?</h3>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                Silakan hubungi <strong class="text-gray-800 dark:text-white">Humas/Admin Sekolah</strong> untuk mereset kata sandi akun Anda. Sampaikan nama lengkap dan email yang terdaftar.
            </p>

            <button
                @click="showForgotModal = false"
                type="button"
                class="w-full rounded-xl bg-gray-100 dark:bg-white/[0.08] py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/[0.12] transition duration-150"
            >
                Mengerti
            </button>
        </div>
    </div>

</div>

{{-- ===================== FLOATING DARK MODE TOGGLE ===================== --}}
<script>
    function toggleSipklTheme() {
        var isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        if (window.Alpine && Alpine.store('theme')) {
            Alpine.store('theme').theme = isDark ? 'dark' : 'light';
        }
    }
</script>

<div class="fixed bottom-6 right-6 z-[9999]">
    <button
        onclick="toggleSipklTheme()"
        class="inline-flex h-12 w-12 items-center justify-center rounded-full
               bg-white dark:bg-amoled-surface border border-gray-200 dark:border-amoled-border
               text-gray-500 dark:text-gray-400 shadow-xl
               hover:bg-gray-50 dark:hover:bg-white/[0.08] hover:text-gray-700 dark:hover:text-white
               transition-all focus:outline-none focus:ring-2 focus:ring-school-blue focus:ring-offset-2
               focus:ring-offset-white dark:focus:ring-offset-amoled"
        title="Ganti tema">
        {{-- Ikon bulan — tampil saat light mode --}}
        <svg class="block dark:hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
        {{-- Ikon matahari — tampil saat dark mode --}}
        <svg class="hidden dark:block w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
        </svg>
    </button>
</div>

{{-- Alpine Store: sinkron tema dengan app.blade.php --}}
<script>
    document.addEventListener('alpine:init', () => {
        if (!Alpine.store('theme')) {
            Alpine.store('theme', {
                init() {
                    const saved = localStorage.getItem('theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    this.theme = saved ? saved : (prefersDark ? 'dark' : 'light');
                    this.apply();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.apply();
                },
                apply() {
                    document.documentElement.classList.toggle('dark', this.theme === 'dark');
                }
            });
        }
    });
</script>

</body>
</html>
