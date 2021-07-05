const mix = require('laravel-mix');
const WebpackShellPlugin = require('webpack-shell-plugin');
const themeInfo = require('./theme.json');
mix.js(['resources/js/vueinit.js'], 'assets/js/vueinit.js');
mix.copy('resources/js/library/jquery.min.js', 'assets/js/jquery.min.js');

mix.sass('resources/scss/backend.scss', 'assets/css/backend.css');

mix.styles(['resources/css/custom.css', 'assets/css/backend.css'], 'assets/css/app.css');

mix.scripts([
    'assets/js/vueinit.js',
], 'assets/js/app.js');
mix.copyDirectory('resources/images', 'assets/images');
mix.copyDirectory('resources/vendor/login-layout', 'assets/login-layout');

/**
 * Copy node module
 */

mix.copyDirectory('node_modules/admin-lte', 'assets/vendor/admin-lte');
mix.copyDirectory('resources/vendor/font-awesome', 'assets/vendor/font-awesome');
mix.copyDirectory('node_modules/bootstrap', 'assets/vendor/bootstrap');
mix.copyDirectory('resources/js/library/jquery-ui', 'assets/vendor/jquery-ui');
mix.copyDirectory('node_modules/font-awesome/fonts', '../../public/fonts/vendor/font-awesome');
mix.copyDirectory('node_modules/element-ui/lib/theme-chalk/fonts', '../../public/fonts/vendor/element-ui/lib/theme-chalk');
mix.copyDirectory('resources/js/library/tinymce4.7.5', 'assets/vendor/tinymce');


/**
 * Publishing the assets
 */
mix.webpackConfig({
    resolve: {symlinks: false},
    plugins: [
        new WebpackShellPlugin({onBuildExit: ['php ../../artisan theme:publish ' + themeInfo.code]}),
    ],
});
