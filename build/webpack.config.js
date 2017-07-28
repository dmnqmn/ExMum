const webpack = require('webpack')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const ManifestPlugin = require('webpack-manifest-plugin')
const path = require('path')
const readdir = require('readdir')
const { rules, extractLess } = require('./webpack.rules.js')

const {
  assetsPath, jsEntryPath, publicPath, publicJsPath,
  publicCssPath, resolvedPublicPath, resolvedDllInfo
} = require('./config.js')

const entries = {
}

readdir.readSync(jsEntryPath, ['**.js']).forEach((entry) => {
  entry = entry.split('.').slice(0, -1).join('.')
  entries[entry] = `${jsEntryPath}${entry}`
})

let jsFilename

switch (process.env.NODE_ENV) {
case 'production':
  jsFilename = 'js/[name].[hash].js'
  break
case 'development':
default:
  jsFilename = 'js/[name].js'
}

module.exports = {
  watch: process.env.NODE_ENV === 'development',
  entry: entries,
  output: {
    filename: jsFilename,
    path: resolvedPublicPath
  },
  resolve: {
    extensions: ['.js', '.json', '.vue', '.less', '.css', '*']
  },
  module: {
    rules
  },
  plugins: [
    extractLess,
    new webpack.DllReferencePlugin({
      manifest: resolvedDllInfo
    }),
    new ManifestPlugin({ fileName: 'manifest.json' })
  ]
}
