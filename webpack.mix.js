const {mix} = require('laravel-mix');
const {glob} = require('glob');

mix.disableNotifications();

mix.webpackConfig({
    resolve: {
        extensions: ['.ts']
    },
    module: {
        rules: [
            {
                test: /\.ts$/,
                loader: 'ts-loader'
            }
        ]
    }
});

mix.autoload({
    jquery : ['$', 'window.jQuery', 'jQuery']
});

mix.js('resources/assets/js/common/common.js', 'public/dist/js/common.js');
mix.sass('resources/assets/sass/common/bootstrap.scss', 'public/dist/css');

glob.sync('./resources/assets/sass/styles/*.{scss,sass}').forEach(function (fn) {
    mix.sass(fn, 'public/dist/css');
});

mix.js('./resources/assets/js/programs/schedule.ts', 'public/dist/js/schedule.js');
mix.js('./resources/assets/js/programs/create.ts', 'public/dist/js/create.js');

mix.extract([
    'jquery',
    'bootstrap-sass',
    'bootstrap-table',
    'fullcalendar',
    'moment',
    'eonasdan-bootstrap-datetimepicker'
], 'public/dist/extract/common-vendors.js');
