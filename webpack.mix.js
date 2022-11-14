const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

const theme = process.env.APP_THEME;
mix.js(`resources/js/app.js`, 'public/js/default/')
	.react()
    .version();

mix.js(`resources/js/${theme}/app.js`, `public/themes/${theme}/`)
	.react()
    .version();

/**
 * con extract extraemos las librerias del código y así podemos crear varios componetes separados sin recargar todo el código
 * en cada archivo.
 *
 * Al utilizarlo, añadir los scripts en el siguiente orden
 * <script defer src="{{ URL::asset('/default_v2/js/manifest.js') }}"></script>
 * <script defer src="{{ URL::asset('/default_v2/js/vendor.js') }}"></script>
 * <script defer src="{{ URL::asset('/default_v2/js/TiempoRealComponents.js') }}"></script>
 *  */

/* mix.js(`resources/js/TiempoRealComponents.js`, 'public/default_v2/js/')
	.react()
	.extract(['react', 'react-dom'])
    .version(); */

