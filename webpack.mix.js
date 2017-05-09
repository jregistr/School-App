const { mix } = require('laravel-mix');


mix.js('resources/assets/js/common/common.js', 'public/dist/js')
    .sass('resources/assets/sass/common/bootstrap.scss', 'public/dist/css');
