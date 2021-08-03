require('dotenv').config();
const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

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
        filename: "bundle.js",
        path: path.resolve(__dirname, "./public/js/"),
        publicPath: "/js/"
    },
    module: {
        rules: [
            {
                test: /\.s?css$/i,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: './public/css/',
                        },
                    },
                    'css-loader',
                    'sass-loader',
                ],
            },
            {
                test: /\.html\.tpl$/,
                loader: 'ejs-loader',
                options: {
                    esModule: false
                }
            },
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '../css/[name].css',
        }),
        new webpack.ProvidePlugin({
            _: 'lodash'
        })
    ]
};
