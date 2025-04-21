import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, daisyui],
    daisyui: {
        themes: [
            {
                "modern-dark": {
                    "primary": "#3B82F6",    // blue-500
                    "secondary": "#1E40AF",   // blue-800
                    "accent": "#60A5FA",      // blue-400
                    "neutral": "#1F2937",     // gray-800
                    "base-100": "#111827",    // gray-900
                    "base-200": "#0F172A",    // slate-900
                    "base-300": "#020617",    // slate-950
                    "info": "#0EA5E9",        // sky-500
                    "success": "#22C55E",     // green-500
                    "warning": "#F59E0B",     // amber-500
                    "error": "#EF4444",       // red-500
                },
                "modern-light": {
                    "primary": "#3B82F6",    // blue-500
                    "secondary": "#1E40AF",   // blue-800
                    "accent": "#60A5FA",      // blue-400
                    "neutral": "#E5E7EB",     // gray-200
                    "base-100": "#FFFFFF",    // white
                    "base-200": "#F3F4F6",    // gray-100
                    "base-300": "#E5E7EB",    // gray-200
                    "info": "#0EA5E9",        // sky-500
                    "success": "#22C55E",     // green-500
                    "warning": "#F59E0B",     // amber-500
                    "error": "#EF4444",       // red-500
                },
            },
        ],
        darkTheme: "modern-dark",
        base: true,
        styled: true,
        utils: true,
        prefix: "",
        logs: true,
    },
};
