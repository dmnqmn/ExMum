const webpack = require('webpack')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const ManifestPlugin = require('webpack-manifest-plugin')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')
const { providePlugin, envPlugin } = require('./commonPlugins.js')
const path = require('path')
const readdir = require('readdir')

const alias = require('./commonAlias.js')
const {
  assetsPath, jsEntryPath, publicPath, publicJsPath,
  publicCssPath, resolvedPublicPath, resolvedDllInfo,
  resolvedAssetsPath
} = require('./config.js')

let cssFilename = 'css/dll.[contenthash].css'

const extractLess = new ExtractTextPlugin({
  filename: cssFilename
})

const rules = [
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
    test: /\.css$/i,
    use: extractLess.extract(['css-loader'])
  },
  {
    test: /\.js$/,
    exclude: /node_modules/,
    loader: 'babel-loader'
  },
  {
    test: /\.(woff|woff2|eot|ttf|otf)$/,
    use: [
      {
        loader: 'file-loader',
        options: {
          name: '[name].[hash].[ext]',
          publicPath: resolvedPublicPath,
          outputPath: 'fonts/'
        }
      }
    ]
  },
  {
    test: /\.(svg)$/,
    use: [
      {
        loader: 'file-loader',
        options: {
          name: '[name].[hash].[ext]',
          publicPath: resolvedPublicPath,
          outputPath: 'images/'
        }
      }
    ]
  }
]

let optPlugins

switch (process.env.NODE_ENV) {
case 'production':
  optPlugins = [new UglifyJsPlugin({
    test: /.js$/,
    exclude: 'node_modules'
  })]
  break
case 'development':
default:
  optPlugins = []
}

module.exports = {
  name: 'dll',
  entry: [
    `${assetsPath}/js/dll.js`
  ],
  output: {
    filename: 'js/dll.[hash].js',
    path: resolvedPublicPath,
    library: 'dll_[hash]'
  },
  module: {
    rules
  },
  resolve: {
    extensions: ['.js', '.json', '.vue', '.less', '.css', '*'],
    alias
  },
  plugins: [
    extractLess,
    new webpack.DllPlugin({
        name: 'dll_[hash]',
        path: resolvedDllInfo
    }),
    new ManifestPlugin({ fileName: 'dll-manifest.json' }),
    providePlugin,
    envPlugin
  ].concat(optPlugins)
}
