'use strict'

const { VueLoaderPlugin } = require('vue-loader/lib/index')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const Dotenv = require('dotenv-webpack')
const path = require('path')
require('dotenv').config()

module.exports = {
  mode: 'development',
  entry: [
    './src/main.js',
    './src/styles/index.scss'
  ],
  stats: {
    children: false
  },
  output: {
    filename: '[name].js',
    chunkFilename: '[name].js',
    publicPath: '/',
    pathinfo: false,
    path: path.resolve(__dirname, 'dist')
  },
  /** @see https://webpack.js.org/configuration/devtool/ */
  devtool: 'eval',
  devServer: {
    publicPath: '/',
    contentBase: './dist',
    host: process.env.WEBPACK_HOST_DEV || 'localhost',
    hot: true,
    clientLogLevel: 'error',
    disableHostCheck: true,
    proxy: {
      '/api/*': {
        target: process.env.PROXY_SERVER || 'http://localhost/',
        changeOrigin: true
      }
    }
  },
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          'vue-style-loader',
          'css-loader',
          'sass-loader'
        ]
      },
      {
        test: /\.(png|svg|jpe?g|gif)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              outputPath: 'web/images'
            }
          }
        ]
      },
      {
        test: /\.(eot|svg|ttf|woff|woff2)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              outputPath: 'web/files'
            }
          }
        ]
      },
      {
        test: /\.vue$/,
        use: 'vue-loader'
      },
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: [
          'babel-loader',
          'eslint-loader'
        ]
      }
    ]
  },
  plugins: [
    new Dotenv,
    new VueLoaderPlugin,
    new HtmlWebpackPlugin({
      filename: 'index.html',
      template: './index.html',
      inject: true,
      chunksSortMode: 'none',
      isDev: true
    }),
    // new (require('webpack-bundle-analyzer').BundleAnalyzerPlugin)()
  ],
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      '@': path.resolve(__dirname, './src/'),
      'scss': path.resolve(__dirname, './src/styles/')
    }
  }
}
