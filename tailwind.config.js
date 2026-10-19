const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans:    ['Poppins', ...defaultTheme.fontFamily.sans],
                heading: ['NORD', 'Poppins', 'sans-serif'],
                accent:  ['Instrument Serif', 'serif'],
                body:    ['Poppins', ...defaultTheme.fontFamily.sans],
                nord:       ['Nord', 'Poppins', 'sans-serif'],
                instrument: ['"Instrument Serif"', 'Georgia', 'serif'],
                poppins:    ['Poppins', 'sans-serif'],
            },

            borderRadius: {
                button: '8px',
            },
            boxShadow: {
                brand:        '0 4px 16px rgba(122, 43, 74, 0.18)',
                'brand-md':   '0 8px 24px rgba(122, 43, 74, 0.22)',
                teal:         '0 4px 16px rgba(26, 122, 94, 0.18)',
            },
            backgroundImage: {
                'brand-gradient':    'linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%)',
                'sidebar-gradient':  'linear-gradient(160deg, #1C1C1C 0%, #3D1A28 60%, #1D5A4B 100%)',
                'card-gradient':     'linear-gradient(135deg, #FDF2F8 0%, #F4C9DF 100%)',
                'teal-gradient':     'linear-gradient(135deg, #1D5A4B 0%, #1A7A5E 100%)',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
