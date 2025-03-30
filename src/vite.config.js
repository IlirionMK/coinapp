import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
    },

    plugins: [
        tailwindcss(),
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
    ],
})
