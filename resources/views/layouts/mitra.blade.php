<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Portal Mitra - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js Initialization -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                updateTheme() {
                    if (this.theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-amoled text-gray-800 dark:text-gray-100 min-h-screen flex flex-col">
    <div class="flex-grow flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
        <!-- Logo Section -->
        <div class="mb-8 text-center">
            <div class="flex flex-col items-center gap-2">
                <div class="w-20 h-20 bg-school-blue/10 dark:bg-white/10 rounded-2xl flex items-center justify-center p-2 mb-2">
                     {{-- Fallback SVG Logo or Placeholder --}}
                     <svg class="w-12 h-12 text-school-blue dark:text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                     </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-widest uppercase">SIM-PKL</h1>
                <p class="text-sm font-medium text-gray-500 dark:text-amoled-text tracking-wider uppercase">SMK Negeri 2 Magelang</p>
            </div>
        </div>

        <!-- Main Content (Form Card) -->
        <main class="w-full max-w-2xl">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-8 text-center">
            <p class="text-xs text-gray-400 dark:text-gray-600">
                &copy; {{ date('Y') }} SIM-PKL SMKN 2 Magelang. Hak Cipta Dilindungi.
            </p>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
