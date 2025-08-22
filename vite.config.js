import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig(({ mode }) => {
	// Cargar variables de entorno según el modo (development, production, etc.)
	const env = loadEnv(mode, process.cwd(), 'APP_THEME');
	const theme = env.APP_THEME || 'default';

	return {
		plugins: [
			laravel({
				input: [
					'resources/css/app.css',
					`resources/js/${theme}/app.jsx`,
				],
				refresh: true,
			}),
			react(),
		],
		build: {
			outDir: `public/themes/${theme}/build`,
			emptyOutDir: true,
		}
	}
});
