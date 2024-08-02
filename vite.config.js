import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                // 'resources/css/laravel-views.css',
                'resources/css/tippy-637.css',
                'resources/css/spotlight-078.css',
                // 'resources/css/laravel-views.css',
                'resources/js/app.js',
                // 'resources/js/laravel-views.js',
                'resources/js/preline.js',
                'node_modules/hammerjs/src/hammer.js'
                // 'node_modules/tw-elements/dist/js/tw-elements.umd.min.js'
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
    ],
});
