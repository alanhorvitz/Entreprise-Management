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
                light: {
                    "primary": "#3B82F6",
                    "secondary": "#1E40AF",
                    "accent": "#60A5FA",
                    "neutral": "#1F2937",
                    "base-100": "#FFFFFF",
                    "base-200": "#F3F4F6",
                    "base-300": "#E5E7EB",
                    "info": "#0EA5E9",
                    "success": "#22C55E",
                    "warning": "#F59E0B",
                    "error": "#EF4444",
                },
                dark: {
                    "primary": "#3B82F6",
                    "secondary": "#1E40AF",
                    "accent": "#60A5FA",
                    "neutral": "#1F2937",
                    "base-100": "#111827",
                    "base-200": "#0F172A",
                    "base-300": "#020617",
                    "info": "#0EA5E9",
                    "success": "#22C55E",
                    "warning": "#F59E0B",
                    "error": "#EF4444",
                }
            }
        ],
        darkTheme: "dark",
        base: true,
        styled: true,
        utils: true,
        prefix: "",
        logs: true,
    },
};
