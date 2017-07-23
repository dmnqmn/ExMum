const webpack = require('webpack')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const ManifestPlugin = require('webpack-manifest-plugin')
const path = require('path')
const readdir = require('readdir')

const assetsPath = './resources/assets/'
const jsEntryPath = `${assetsPath}js/entry/`
const publicPath = './public/'
const publicJsPath = `${publicPath}/js/`
const publicCssPath = `${publicPath}/css/`

const entries = {
}

readdir.readSync(jsEntryPath, ['**.js']).forEach((entry) => {
  entry = entry.split('.').slice(0, -1).join('.')
  entries[entry] = `${jsEntryPath}${entry}`
})

let jsFilename
let cssFilename
let optPlugins

switch (process.env.NODE_ENV) {
case 'production':
  jsFilename = 'js/[name].[hash].js'
  cssFilename = 'css/[name].[hash].css'
  optPlugins = [
    new CleanWebpackPlugin([publicJsPath, publicCssPath]),
    new ManifestPlugin()
  ]
  break
case 'development':
default:
  jsFilename = 'js/[name].js'
  cssFilename = 'css/[name].css'
  optPlugins = []
}

const extractLess = new ExtractTextPlugin({
  filename: cssFilename
})

module.exports = {
  watch: process.env.NODE_ENV === 'development',
  entry: entries,
  output: {
    filename: jsFilename,
    path: path.resolve(__dirname, 'public')
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: [
            'vue-style-loader',
            {
              loader: 'css-loader',
              options: {
                minimize: process.env.NODE_ENV === 'production'
              }
            },
            'less-loader'
          ]
        }
      },
      {
        test: /\.less$/i,
        use: extractLess.extract(['css-loader', 'less-loader'])
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      }
    ]
  },
  plugins: [
    extractLess
  ].concat(optPlugins)
}
