"use strict";

var path = require('path')
var deepCopy = require('deepcopy')
var rootConfig = deepCopy(require(path.join('..', '..', 'root.config.js')))

var base = path.join(__dirname, 'js')

var entry = path.join(base, 'semester-raw.js')

var webpackConfig = rootConfig.webpackConfig

webpackConfig.entry = entry

webpackConfig.output = {
  base: base,
  filename: 'semester.js'
}

function gulpTaskFn(gulp, plugins) {
  return function() {
    return gulp.src(entry)
      .pipe(plugins.webpack(webpackConfig, require('webpack')))
      .pipe(gulp.dest(base))
  }
}

module.exports = {
  gulpTaskName: 'webpack-admin-academics-semester',
  gulpTaskFn: gulpTaskFn,
  destDir: base,
  minifyJs: [path.join(base, 'semester.js')]
}
