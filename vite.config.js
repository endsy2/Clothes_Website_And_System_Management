
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'], // Adjust if needed
            refresh: true, // Enable hot reloading
        }),
    ],
    server: {
        host: 'localhost',
        port: 5173, // Make sure this matches the port set in your .env
    },
});
