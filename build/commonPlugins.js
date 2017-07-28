const webpack = require('webpack')

const providePlugin = new webpack.ProvidePlugin({
    jQuery: 'jquery',
    $: 'jquery',
    jquery: 'jquery'
})

const envPlugin = new webpack.DefinePlugin({
    'process.env': {
        NODE_ENV: `"${process.env.NODE_ENV}"`
    }
})

module.exports = {
    providePlugin,
    envPlugin
}
