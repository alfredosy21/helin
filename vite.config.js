import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/cms/css/app.css',
                'resources/cms/js/app.js',
                'resources/cms/js/dashboard.js',
                'resources/cms/js/auth.js',
            ],
            refresh: true,
        }),
    ],
});
