const baseConfig = require('./webpack.config.js');
const devConfig = Object.assign(baseConfig,{
    devtool: 'inline-source-map'
});


module.exports = devConfig;
