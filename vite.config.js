import vue from '@vitejs/plugin-vue'
import {defineConfig} from 'vite'
import laravel from 'laravel-vite-plugin'
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            '@assets': path.resolve(__dirname, 'resources/assets'),
            '@js': path.resolve(__dirname, 'resources/js'),
            '@components': path.resolve(__dirname, 'resources/js/components'),
            '@style': path.resolve(__dirname, 'resources/js/style'),
            '@styleVars': path.resolve(__dirname, 'resources/js/style/variables'),
        },
    },
})
