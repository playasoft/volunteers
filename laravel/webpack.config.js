require('dotenv').config();
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
// I don't really like doing it this way but it works for a limited number
// of configuration options.
const socketsEnabled = process.env.WEBSOCKETS_ENABLED &&
          process.env.WEBSOCKETS_ENABLED != ('false' || '0');

const appEntry = socketsEnabled ?
          './resources/app.js' :
          './resources/app_nosockets.js';

module.exports = {
    entry:
    {
        main: appEntry
    },
    output:
    {
        filename: './public/js/bundle.js'
    },
    module: {
        rules: [
            { // sass / scss loader for webpack
                test: /\.scss$/,
                loader: ExtractTextPlugin.extract(['css-loader', 'sass-loader'])
            },
            {
                test: /\.html\.tpl$/,
                loader: 'ejs-loader'
            },
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin({ // define where to save the file
            filename: 'public/css/[name].css',
            allChunks: true
        }),
        new webpack.ProvidePlugin({
            _: 'lodash'
        })
    ]
};
