require('dotenv').config();
const webpack = require('webpack');
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
            {
                test: /\.s[ac]ss$/i,
                use: [
                  // Creates `style` nodes from JS strings
                  'style-loader',
                  // Translates CSS into CommonJS
                  'css-loader',
                  // Compiles Sass to CSS
                  'sass-loader',
                ],
            },
            {
                test: /\.html\.tpl$/,
                loader: 'ejs-loader',
                options: {
                    variable: 'data',
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
        new webpack.ProvidePlugin({
            _: 'lodash'
        })
    ]
};
