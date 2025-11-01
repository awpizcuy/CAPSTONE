import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        screens: {
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
        },

        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                'primary': {
                    'DEFAULT': '#005691',
                    '50': '#E0F0F7',
                    '100': '#B3D6E7',
                    '600': '#004778',
                    '700': '#00335A',
                },
                'accent': {
                    '50': '#E6F9FB',
                    '100': '#BFEFF2',
                    '200': '#8FE0E7',
                    '300': '#5FD1DC',
                    '400': '#2FC2D1',
                    'DEFAULT': '#00BCD4',
                    '600': '#00A1B0',
                    '700': '#007B86',
                    '800': '#00565C',
                    '900': '#003F42',
                },
            }
        },
    },

    plugins: [forms],
};
