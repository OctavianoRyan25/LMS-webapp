import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans:    ['Inter', 'sans-serif'],
                display: ['DM Sans', 'sans-serif'],
            },
            colors: {
                navy: {
                    900: '#0F172A',
                    800: '#1E293B',
                    700: '#2D3A4F',
                    600: '#3D4F68',
                },
                brand: {
                    50:  '#EEF2FF',
                    100: '#E0E7FF',
                    200: '#C7D2FE',
                    300: '#A5B4FC',
                    400: '#818CF8',
                    500: '#6366F1',
                    600: '#4F46E5',
                    700: '#4338CA',
                },
            },
            boxShadow: {
                'card':  '0 1px 3px 0 rgba(0,0,0,.07), 0 1px 2px -1px rgba(0,0,0,.07)',
                'card-hover': '0 10px 30px -4px rgba(99,102,241,.15)',
            },
        },
    },
    plugins: [],
};
