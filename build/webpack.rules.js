const ExtractTextPlugin = require('extract-text-webpack-plugin')
const { resolvedPublicPath } = require('./config.js')


let cssFilename

switch (process.env.NODE_ENV) {
case 'production':
  cssFilename = 'css/[name].[contenthash].css'
  break
case 'development':
default:
  cssFilename = 'css/[name].css'
}

const extractLess = new ExtractTextPlugin({
  filename: cssFilename
})

module.exports = {
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
  ],
  extractLess
}
