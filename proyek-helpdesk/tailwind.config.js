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
                // Warna Primer (Korporat, Keandalan)
                'primary': {
                    'DEFAULT': '#005691', // Biru Navy (Aksen utama)
                    '50': '#E0F0F7',
                    '100': '#B3D6E7',
                    '600': '#004778',
                    '700': '#00335A',
                },
                // Warna Sekunder/Aksen (Energi, Tombol Aksi)
                'accent': '#00bcd4', // Hijau Teal
            }
        },
    },

    plugins: [forms],
};
