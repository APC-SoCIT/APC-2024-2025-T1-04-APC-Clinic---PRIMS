import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                nunito: ['Nunito', 'sans-serif'],
            },
            colors: {
                'prims-yellow': {
                    1: '#F4BF4F', // Darker
                },
                'prims-azure': {
                    100: '#1150A3',  // for the login
                    200: '#6F9FBB',  // Lighter
                    300: '#4A8AA3',  // Medium light
                    400: '#2C7491',  // Base color
                    500: '#183A66',  // Base color (your original)
                    600: '#132C53',  // Darker shade
                    700: '#0E1F40',  // Darker
                    800: '#091535',  // Even darker
                    900: '#050F2A',  // Darkest
                },
                'sub-header': {
                    1: '#092F60', 
                },
                'choose-time': {
                    1: '#E3E3E3',
                    2: '#3E3E3E',
                }
            },
            spacing: {
                '3': '0.75rem',
                '4': '1rem',
                '5': '1.25rem',
                '6': '1.5rem',
                '7': '1.75rem',
                '8': '2rem',
                '9': '2.25rem',
                '10': '2.5rem',
                '11': '2.75rem',
                '12': '3rem',
                '13': '3.25rem',
                '14': '3.5rem',
                '15': '3.75rem',
                '16': '4rem',
                '17': '4.25rem',
                '18': '4.5rem',
                '19': '4.75rem',
                '20': '5rem',
            },
            gap: {
                '3': '0.75rem',
                '4': '1rem',
                '5': '1.25rem',
                '6': '1.5rem',
                '7': '1.75rem',
                '8': '2rem',
                '9': '2.25rem',
                '10': '2.5rem',
                '11': '2.75rem',
                '12': '3rem',
                '13': '3.25rem',
                '14': '3.5rem',
                '15': '3.75rem',
                '16': '4rem',
                '17': '4.25rem',
                '18': '4.5rem',
                '19': '4.75rem',
                '20': '5rem',
            },
        },
    },

    plugins: [forms, typography],
};
