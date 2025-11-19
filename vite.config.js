import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/panier.css',
                'resources/css/profil.css',
                'resources/css/messages.css',
                'resources/css/reservations.css',
                'resources/css/publier.css',
                'resources/js/app.js',
                'resources/js/panier.js',
                'resources/js/rechercher.js',
                'resources/js/reservations.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
