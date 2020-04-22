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
    .sass('resources/sass/app.scss', 'public/css');

let chunkFilename = mix.inProduction() ? '[name].[chunkhash].js' : '[name].js';

mix.webpackConfig({
    devServer: { disableHostCheck: true },
    resolve  : {
        alias: {
            '@'         : path.resolve(__dirname, 'resources/js/'),
            'static'    : path.resolve(__dirname, 'resources/static/')
        },
    },
    output: { 
        chunkFilename: 'js/chunks/' + chunkFilename,
        publicPath: '/',
    },
})

if (mix.inProduction())
    mix.version()
else
    mix.sourceMaps()
