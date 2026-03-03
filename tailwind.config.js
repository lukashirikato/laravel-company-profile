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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                heading: ['NORD', 'Poppins', 'sans-serif'],
                accent: ['Instrument Serif', 'serif'],
                body: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // ── FTM SOCIETY BRAND DESIGN TOKENS ──────────────────────────────
                // Primary brand = Power Pink → Burnt Cherry family
                brand: {
                    50:  '#FDF2F8',   // near-white tint
                    100: '#FAE0EE',   // very light petal
                    200: '#F5C4DC',   // light petal / Soft Petals adj.
                    300: '#F1CCE3',   // Soft Petals
                    400: '#EE93B9',   // mid pink
                    500: '#EA6993',   // Power Pink  ← base primary
                    600: '#D94E7A',   // darker pink
                    700: '#B83863',   // deep rose
                    800: '#793451',   // Burnt Cherry ← primary-dark / hover
                    900: '#5A1F3A',   // darkest cherry
                    950: '#3D0E24',   // deepest
                },
                // Secondary brand = Patina Green → Springs Ivy family
                teal: {
                    50:  '#E8F5F2',
                    100: '#C6E8E0',
                    200: '#92D2C5',
                    300: '#5CBBAA',
                    400: '#2DA48F',
                    500: '#00745F',   // Patina Green ← base secondary
                    600: '#006251',
                    700: '#08513C',   // Springs Ivy ← secondary-dark
                    800: '#063D2D',
                    900: '#042A1F',
                    950: '#021610',
                },
                // Neutral warm = Rising → Layl family
                paper: {
                    50:  '#FDFAF8',   // nearly white warm
                    100: '#FAF5EF',
                    200: '#F4EEE6',   // Rising (bg)
                    300: '#EBE0D5',
                    400: '#D5C5B5',
                    500: '#BFA898',
                    600: '#9A8578',
                    700: '#756457',
                    800: '#504640',
                    900: '#26282B',   // Layl (ink)
                    950: '#16181A',
                },
                // Grounded Green (accent / muted success)
                sage: {
                    50:  '#F8FAF0',
                    100: '#EEF3D5',
                    200: '#D2DCA5',   // Grounded Green ← accent
                    300: '#BBCA75',
                    400: '#A3B84A',
                    500: '#88A020',
                    600: '#6B8018',
                    700: '#506010',
                    800: '#384508',
                    900: '#202804',
                },
                // ── SEMANTIC ALIASES ─────────────────────────────────────────────
                primary:   '#EA6993',   // Power Pink
                'primary-dark': '#793451', // Burnt Cherry
                secondary: '#00745F',   // Patina Green
                'secondary-dark': '#08513C', // Springs Ivy
                accent:    '#F1CCE3',   // Soft Petals
                'ink':     '#26282B',   // Layl
                'paper-bg':'#F4EEE6',   // Rising

                // ── LEGACY (preserved for backward compat) ───────────────────────
                maroon: {
                    dark:   '#3d1f1f',
                    medium: '#4d3333',
                },
                rose: {
                    mauve: '#a87575',
                    dusty: '#c89a9a',
                },
                beige: '#d1b5a5',
            },
            borderRadius: {
                button: '8px',
            },
            boxShadow: {
                brand: '0 4px 16px rgba(121, 52, 81, 0.18)',
                'brand-md': '0 8px 24px rgba(121, 52, 81, 0.22)',
                teal: '0 4px 16px rgba(0, 116, 95, 0.18)',
            },
            backgroundImage: {
                'brand-gradient': 'linear-gradient(135deg, #793451 0%, #EA6993 100%)',
                'sidebar-gradient': 'linear-gradient(160deg, #26282B 0%, #3D1A28 60%, #08513C 100%)',
                'card-gradient': 'linear-gradient(135deg, #FDF2F8 0%, #F1CCE3 100%)',
                'teal-gradient': 'linear-gradient(135deg, #08513C 0%, #00745F 100%)',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};

