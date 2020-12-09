const laravelMix = require('laravel-mix');

laravelMix
    .js('resources/js/index.js', 'public/js')
    .sass('resources/scss/index.scss', 'public/css');
