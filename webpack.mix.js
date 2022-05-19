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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

// Bundle the widget code, and transform it into a Blade template
mix.js('resources/js/widget/embed.js', 'public/js').vue();
mix.copy('public/js/embed.js', 'resources/views/widget/widget-script.php');

mix.disableSuccessNotifications();