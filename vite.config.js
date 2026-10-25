import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Remplace la configuration par défaut (Tailwind) : le projet utilise Bootstrap 5 + Sass.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 5 utilise encore @import : on masque les avertissements de dépréciation de Sass
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls', 'if-function'],
            },
        },
    },
});
