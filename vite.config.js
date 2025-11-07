// filepath: /c:/Users/grafr/RMDC/vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Allow external access
        port: 5173,
        strictPort: false,
        hmr: {
            host: 'localhost'
        },
        watch: {
            usePolling: true
        }
    },
    // Build optimizations for production
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});