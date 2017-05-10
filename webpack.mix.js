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

mix.js('resources/assets/js/common/common.js', 'public/dist/js');
mix.sass('resources/assets/sass/common/bootstrap.scss', 'public/dist/css');

glob.sync('./resources/assets/ts/*.ts').forEach(function (fn) {
    mix.js(fn, 'public/dist/js');
});

glob.sync('./resources/assets/sass/styles/*.scss').forEach(function (fn) {
    mix.sass(fn, 'public/dist/css');
});


