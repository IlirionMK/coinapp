import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [vue()],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        watch: {
            usePolling: true,
            interval: 100,
        },
        hmr: {
            host: 'localhost',
            clientPort: 5173,
        },
    },
    cacheDir: 'node_modules/.vite',
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
})
