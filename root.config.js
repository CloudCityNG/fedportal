"use strict";

var webpackConfig = {
  cache: true,
  watch: true,

  externals: {
    'angular': 'angular',
    'jQuery': 'jQuery'
  },

  module: {
    loaders: [
      {test: /\.html$/, loader: 'html'}
    ]
  }
}

module.exports = {
  webpackConfig: webpackConfig,
  root: __dirname
}
