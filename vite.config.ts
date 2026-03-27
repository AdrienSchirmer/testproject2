import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const isSail = process.env.LARAVEL_SAIL === '1';
    const vitePort = Number(process.env.VITE_PORT ?? env.VITE_PORT ?? 5173);
    const appUrl = process.env.APP_URL ?? env.APP_URL ?? 'http://localhost';
    const hmrHost = new URL(appUrl).hostname;

    return {
        server: isSail
            ? {
                  host: '0.0.0.0',
                  port: vitePort,
                  strictPort: true,
                  hmr: {
                      host: hmrHost,
                      port: vitePort,
                  },
              }
            : undefined,
        plugins: [
            laravel({
                input: ['resources/js/app.ts'],
                ssr: 'resources/js/ssr.ts',
                refresh: true,
            }),
            tailwindcss(),
            wayfinder({
                formVariants: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
    };
});
