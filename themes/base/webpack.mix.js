const mix = require('laravel-mix')
const WebpackShellPlugin = require('webpack-shell-plugin')
const themeInfo = require('./theme.json')
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

mix.js('resources/js/app.js', 'assets/js')
    .sass('resources/sass/app.scss', 'assets/css')
    .copyDirectory('images', '../../public/images')
    .copyDirectory('resources/assets/images', 'assets/images')
    .copyDirectory('fonts', '../../public/fonts')
    .styles(['resources/css/main.css'], 'assets/css/main.css');
mix.copy('*-dist.js', '../../public/');
mix.copy('*orker.js', '../../public/');


mix.webpackConfig({
    plugins: [
        new WebpackShellPlugin({onBuildExit: ['php ../../artisan theme:publish ' + themeInfo.code]})

    ],


})
