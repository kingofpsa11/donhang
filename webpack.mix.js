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

// const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
//
// module.exports = {
//     plugins: [
//         // To strip all locales except “en”
//         new MomentLocalesPlugin(),
//
//         // Or: To strip all locales except “en”, “es-us” and “ru”
//         // (“en” is built into Moment and can’t be removed)
//         new MomentLocalesPlugin({
//             localesToKeep: ['es-us', 'ru'],
//         }),
//     ],
// };