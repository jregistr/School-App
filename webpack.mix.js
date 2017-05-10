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

mix.js('resources/assets/js/common/common.js', 'public/dist/js/temp.js');
mix.sass('resources/assets/sass/common/bootstrap.scss', 'public/dist/css');

glob.sync('./resources/assets/sass/styles/*.scss').forEach(function (fn) {
    mix.sass(fn, 'public/dist/css');
});

// programs
mix.js('./resources/assets/js/programs/schedule.js', 'public/dist/js/schedule');



//extract
mix.extract([
        'jquery',
        'lodash',
        'bootstrap-sass'
    ],
    'public/dist/js/c');

//schedule extract
// mix.extract(['fullcalendar'], 'public/dist/js/s');


