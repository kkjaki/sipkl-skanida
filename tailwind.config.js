import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                current: 'currentColor',
                transparent: 'transparent',
                white: '#FFFFFF',
                black: '#1C2434',
                'primary': '#3C50E0',
                'school-blue': '#007BFF',
                'school-orange': '#FFA500',
                'stroke': '#E2E8F0',

                // AMOLED Dark Mode Palette (Zinc-based)
                'amoled': '#000000',           // Pure black background
                'amoled-surface': '#09090b',   // Zinc 950 — cards, sidebar, header
                'amoled-input': '#18181b',     // Zinc 900 — input backgrounds
                'amoled-border': '#27272a',    // Zinc 800 — borders
                'amoled-text': '#a1a1aa',      // Zinc 400 — body text

                'gray': {
                    50: '#F9FAFB',
                    100: '#F3F4F6',
                    200: '#E5E7EB',
                    300: '#D1D5DB',
                    400: '#9CA3AF',
                    500: '#6B7280',
                    600: '#4B5563',
                    700: '#374151',
                    800: '#1F2937',
                    900: '#111827',
                },

                // Legacy tokens (remapped to AMOLED Zinc values)
                'boxdark': '#000000',
                'boxdark-2': '#09090b',
                'strokedark': '#27272a',
                'form-strokedark': '#27272a',
                'meta-4': '#09090b',

                'success': '#219653',
                'danger': '#D34053',
                'warning': '#FFA70B',
            }
        },
    },

    plugins: [forms],
};
