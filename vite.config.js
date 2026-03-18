import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react'; // 1. นำเข้า React Plugin

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.jsx', // 2. เปลี่ยนจาก app.js เป็น app.jsx
            ],
            refresh: true,
        }),
        react(), // 3. เปิดใช้งาน React
    ],
});