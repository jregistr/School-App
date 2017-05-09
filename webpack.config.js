const path = require('path');
const glob = require('glob');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const extractPlugin = new ExtractTextPlugin({
    filename: '[name].build.css'
});

var entries = {};

glob.sync('./resources/assets/js/outs/*.js').forEach(function (fn) {
    var name = path.basename(fn).replace(".js", "");
    entries[name] = fn;
});

glob.sync('./resources/assets/sass/styles/*.scss').forEach(function (fn) {
    var name = "css-".concat(path.basename(fn).replace(".scss", ""));
    entries[name] = fn;
});

module.exports = {
    entry: entries,
    output: {
        path: __dirname + '/public/dist',
        filename: '[name].bundle.js'
    },

    module: {
        rules: [
            {
                test: /\.woff2?$|\.ttf$|\.eot$|\.svg$/,
                loader: "file-loader"
            },
            {
                test: /\.scss$/,
                use: extractPlugin.extract({
                    use: [
                        'css-loader',
                        'sass-loader'
                    ]
                })
            }
        ]
    },

    plugins: [
        extractPlugin
    ]
};
