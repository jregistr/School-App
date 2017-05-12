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

mix.js('resources/assets/js/common/common.js', 'public/dist/js/common.js');
mix.sass('resources/assets/sass/common/bootstrap.scss', 'public/dist/css');

glob.sync('./resources/assets/sass/styles/*.scss').forEach(function (fn) {
    mix.sass(fn, 'public/dist/css');
});

mix.js('./resources/assets/js/programs/schedule.ts', 'public/dist/js/schedule.js');
mix.js('./resources/assets/js/programs/create.ts', 'public/dist/js/create.js');


// mix.copy('node_modules/fullcalendar/dist/fullcalendar.js', 'public/dist/js/fullcalendar.js');
// mix.copy('node_modules/fullcalendar/dist/fullcalendar.css', 'public/dist/css/fullcalendar.css');
//
// mix.js('./resources/assets/js/programs/schedule.js', 'public/dist/js/schedule.js');
//


// glob.sync('./resources/assets/js/programs/*.js').forEach(function (fn) {
//     mix.sass(fn, 'public/dist/js');
// });


// mix.combine([
//     'resources/assets/js/schedule/settingbar.js',
//     'resources/assets/js/schedule/main.js'
// ], 'public/dist/js/schedule.js');


// programs
// mix.js('./resources/assets/js/programs/schedule.js', 'public/dist/js/schedule');


// //extract
// mix.extract([
//         'jquery',
//         'lodash',
//         'bootstrap-sass'
//     ],
//     'public/dist/js/vendors.js');
//
// //schedule extract
// // mix.extract(['fullcalendar'], 'public/dist/js/vendor.js');


