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
            colors: {
                // ───────────────────────────────────────────────────────────────
                // FTM SOCIETY BRAND DESIGN TOKENS — 2025 OFFICIAL
                // Sumber: .kiro/steering/desain.md
                // ───────────────────────────────────────────────────────────────

                // Primary brand = Power Pink → Burnt Cherry family
                brand: {
                    50:  '#FDF2F8',   // near-white tint
                    100: '#FAE0EE',   // very light petal
                    200: '#F8D8E6',   // light petal
                    300: '#F4C9DF',   // Soft Petals (resmi)
                    400: '#F08AB3',   // mid pink
                    500: '#EE4E8B',   // Power Pink (resmi) ← base primary
                    600: '#D43A77',   // darker pink
                    700: '#B02A5E',   // deep rose
                    800: '#7A2B4A',   // Burnt Cherry (resmi) ← primary-dark
                    900: '#5A1F3A',   // darkest cherry
                    950: '#3D0E24',   // deepest
                },

                // Secondary brand = Patina Green → Springs Ivy family
                teal: {
                    50:  '#E8F5F1',
                    100: '#C8E8DD',
                    200: '#92D2C0',
                    300: '#5CBBA3',
                    400: '#2DA486',
                    500: '#1A7A5E',   // Patina Green (resmi) ← base secondary
                    600: '#176A52',
                    700: '#1D5A4B',   // Springs Ivy (resmi) ← secondary-dark
                    800: '#0F3F33',
                    900: '#0A2A22',
                    950: '#021610',
                },

                // Neutral warm = Rising → Layl family
                paper: {
                    50:  '#FEFCF8',
                    100: '#FCF9F2',   // Rising (resmi) — main bg
                    200: '#F8F1E5',
                    300: '#EBE0D5',
                    400: '#D5C5B5',
                    500: '#BFA898',
                    600: '#9A8578',
                    700: '#756457',
                    800: '#504640',
                    900: '#1C1C1C',   // Layl (resmi) ← ink
                    950: '#0E0E0E',
                },

                // Grounded Green (accent / muted success)
                sage: {
                    50:  '#F8FAF0',
                    100: '#EFF3D8',
                    200: '#C5D79B',   // Grounded Green (resmi) ← accent
                    300: '#B0C57E',
                    400: '#95B05B',
                    500: '#7A9540',
                    600: '#607530',
                    700: '#475622',
                    800: '#2E3815',
                    900: '#1A2008',
                },

                // ── SEMANTIC ALIASES (yang dipakai di banyak komponen) ──────────
                primary:           '#EE4E8B',   // Power Pink
                'primary-dark':    '#7A2B4A',   // Burnt Cherry
                secondary:         '#7A2B4A',   // Burnt Cherry (alias kompat)
                'secondary-dark':  '#1D5A4B',   // Springs Ivy
                accent:            '#1A7A5E',   // Patina Green
                'light-pink':      '#F4C9DF',   // Soft Petals
                'cream':           '#FCF9F2',   // Rising
                'dark':            '#1C1C1C',   // Layl
                'springs-ivy':     '#1D5A4B',
                'grounded-green':  '#C5D79B',
                'power-pink':      '#EE4E8B',
                'burnt-cherry':    '#7A2B4A',
                'soft-petals':     '#F4C9DF',
                'patina-green':    '#1A7A5E',
                'layl':            '#1C1C1C',
                'rising':          '#FCF9F2',
                'ink':             '#1C1C1C',
                'paper-bg':        '#FCF9F2',

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
