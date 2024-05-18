import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            'jquery': path.resolve(__dirname, 'node_modules/jquery/dist/jquery.min.js'),
            'bootstrap': path.resolve(__dirname, 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'),
        },
    },
});
