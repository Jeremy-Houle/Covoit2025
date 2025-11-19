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
                'resources/js/reservations.js',
                'resources/js/presentation.js',
                'resources/js/accueil.js',
                'resources/js/animations/about-animations.js',
                'resources/js/animations/contact-animations.js',
                'resources/js/animations/connexion-animations.js',
                'resources/js/animations/inscription-animations.js',
                'resources/js/animations/messages-animations.js',
                'resources/js/animations/profil-animations.js',
                'resources/js/animations/edit-profil-animations.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
