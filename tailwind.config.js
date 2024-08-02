import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        'vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        'vendor/laravel/jetstream/**/*.blade.php',
        'storage/framework/views/*.php',
        'app/View/*.php',
        'resources/views/**/*.blade.php',
        // https://preline.co/docs/index.html
        'node_modules/preline/dist/*.js',
        'src/**/*.{html,js,ts,jsx,tsx}',
        // https://tailwind-elements.com/docs/standard/getting-started/quick-start/
        // 'node_modules/tw-elements/dist/js/**/*.js'
    ],

    safelist: [
        'translate-x-0',
        {
            // force vite to include tailwind colours when compiling
            pattern: /(bg|text)-(fuchsia|purple|gray|red|green|blue|orange|amber|lime|emerald|violet|sky|cyan|pink|rose)-(100|200|300|500|700|900)/,
        },
    ],

    theme: {
        // screens: {
        // DEV NOTE !!!!! IF YOU SPECIFY ONE, YOU NEED TO SPECIFY ALL THAT YOU WILL USE !!!!!
        // ? need this? 'xs': '475px' // custom
        // 'sm': '640px',
        // Default Tailwind Screen Sizes
        // https://tailwindcss.com/docs/responsive-design#overview
        // sm	640px	@media (min-width: 640px)
        // md	768px	@media (min-width: 768px)
        // lg	1024px	@media (min-width: 1024px)
        // xl	1280px	@media (min-width: 1280px)
        // 2xl	1536px	@media (min-width: 1536px)
        // },
        // Refs:
        // https://www.toptal.com/responsive-web/introduction-to-responsive-web-design-pseudo-elements-media-queries
        // https://www.browserstack.com/guide/ideal-screen-sizes-for-responsive-design
        screens: {
            'xs': '320px',
            'qs': '480px', // iPhone Pro 14
            'sm': '640px', // => @media (min-width: 640px) { ... }
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
        },
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans]
            },
            fontSize: {
                xxs: '0.625rem'
            },
            fontWeight: {},
            // padding works for all padding: "pl-?" "pb-?" etc.
            padding: {
                18: '72px',     // 72px
            },
            height: {
                20: '5rem',     // 80px
                30: '7.5rem',   // 120px
                40: '10rem',    // 160px
                50: '12.5rem',  // 200px
            },
            width: {
                12: '48px',     // 48px
                18: '72px',     // 72px
                20: '5rem',     // 80px
                26: '6.5rem',   // 104px
                30: '7.5rem',   // 120px
                40: '10rem',    // 160px
                50: '12.5rem',  // 200px
                56: '14rem',    // 224px
                60: '15rem',    // 240px
                70: '17.5rem',  // 280px
                80: '20rem',    // 320px <--------- xs
                90: '22.5rem',
                100: '25rem',
                110: '27.5rem',
                120: '30rem',   // 480px <--------- qs
                130: '32.5rem',
                140: '35rem',
                160: '40rem',   // 640px <--------- sm
                200: '50rem',   // 800px
                240: '60rem',   // 960px
                260: '65rem',   // 1040px
            },
            minHeight: {
                6: '24px',
                10: '40px',
                50: '12.5rem',  // 200px
                60: '15rem',    // 240px
            },
            minWidth: {
                3: '12px',
                6: '24px',
                7: '28px',
                15: '60px',
                60: '15rem',    // 240px
            },
            maxWidth: {
                10: '2.5rem',   // 40px
                20: '5rem',     // 80px
                26: '6.5rem',   // 104px
                30: '7.5rem',   // 120px
                34: '8.5rem',   // 136px
                40: '10rem',    // 160px
                44: '11rem',    // 176px
                50: '12.5rem',  // 200px
                56: '14rem',    // 224px
                60: '15rem',    // 240px
                70: '17.5rem',  // 280px
                80: '20rem',    // 320px <--------- xs
                90: '22.5rem',
                100: '25rem',
                110: '27.5rem',
                120: '30rem',   // 480px <--------- qs
                130: '32.5rem',
                140: '35rem',
                160: '40rem',   // 640px <--------- sm
                200: '50rem',   // 800px
                240: '60rem',   // 960px
                260: '65rem',   // 1040px
            },
            boxShadow: {},
            rotate: {
                '270': '270deg',
            },
            zIndex: {
                'side-nav': '60',
                '99': '99',
                '100': '100',
                // modal/off-canvas
                '150': '150',
                'loading': '150',
                'modal-overlay': '149',
                'modal': '150',
                'off-canvas-lvl-2': '151',
                // modal image view/upload (e.g. Client Images)
                '154': '154',
                'modal-1-overlay': '154',
                '155': '155',
                'modal-1': '155',
                'modal-1-btn': '175',
                // Livewire Alerts
                '1000': '1000',
                'alert': '1000',
                // Confirmation Modals
                'confirm-overlay': '1499',
                'confirm': '1500',
                'tooltip': '5000',
                // --
                // Must be top most
                'critical': '10000'
            }
        }
    },

    plugins: [
        forms,
        typography,
        require('preline/plugin')
        // require('tw-elements/dist/plugin.cjs')
    ],
    darkMode: 'class'
};
