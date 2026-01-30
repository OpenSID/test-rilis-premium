import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Konfigurasi untuk root index.php (tidak ada public folder)
            publicDirectory: '',
            buildDirectory: 'build',
        }),
    ],
    server: {
        // Pengaturan untuk development server
        middlewareMode: false,
    },
    build: {
        // Output manifest untuk asset handling
        manifest: true,
        outDir: 'build',
    },
});

