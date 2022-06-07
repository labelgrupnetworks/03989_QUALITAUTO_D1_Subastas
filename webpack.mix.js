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
mix.react(`resources/js/app.js`, 'public/js/default/')
    .version();

mix.react(`resources/js/${theme}/app.js`, `public/themes/${theme}/`)
    .version();

mix.react(`resources/js/TiempoRealComponents.js`, 'public/js/default/')
    .version();
