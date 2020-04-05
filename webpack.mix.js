const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/dashboards/black/src/main.js", "public/js/main-1.3.1.js");

// mix.js('resources/js/website/app.js', 'public/js/app-1.3.1.js')
//     .extract(['vue'])
//     .sourceMaps()
// .sass('resources/sass/app.scss', 'public/css');
