const mix = require('laravel-mix');

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

mix.js('resources/js/login.js', 'public/js')
    .js('resources/js/register.js', 'public/js')
    .js('resources/js/welcome.js', 'public/js')
    .js('resources/js/error.js', 'public/js')
    .js('resources/js/tweet.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/tweet.scss', 'public/css');

mix.autoload({
  vue: ['Vue', 'window.Vue'],
  axios: ['axios', 'window.axios']
});
