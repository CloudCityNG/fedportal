"use strict";

var path = require('path')
var webpack = require('webpack')
var rootConfig = require(path.join('..', '..', '..', '..', 'root.config.js'))

var base = __dirname

var entry = path.join(base, 'grade-student.js')

var webpackConfig = rootConfig.webpackConfig

webpackConfig.entry = entry

webpackConfig.output = {
  base: base,
  filename: 'grade-student.js'
}

webpackConfig.resolve = [base]

function gulpTaskFn(gulp, plugins) {
  return function() {
    return gulp.src(entry)
      .pipe(plugins.webpack(webpackConfig, webpack))
      .pipe(plugins.sourcemaps.init())
      .pipe(plugins.uglify())
      .pipe(plugins.rename({suffix: '.min'}))
      .pipe(plugins.sourcemaps.write('.'))
      .pipe(gulp.dest(base))
  }
}

module.exports = {
  gulpTaskName: 'webpack-admin-academics-assessment-grade-student',
  gulpTaskFn: gulpTaskFn
}
