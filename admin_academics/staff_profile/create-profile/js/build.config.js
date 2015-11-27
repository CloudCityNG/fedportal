"use strict";

var path = require('path')
var deepCopy = require('deepcopy')
var rootConfig = deepCopy(require(path.join('..', '..', '..', '..', 'root.config.js')))

var base = __dirname

var entry = path.join(base, 'create-profile-raw.js')

var webpackConfig = rootConfig.webpackConfig

webpackConfig.entry = entry

webpackConfig.output = {
  base: base,
  filename: 'create-profile.js'
}

function gulpTaskFn(gulp, plugins) {
  return function() {
    return gulp.src(entry)
      .pipe(plugins.webpack(webpackConfig, require('webpack')))
      .pipe(gulp.dest(base))
  }
}

module.exports = {
  gulpTaskName: 'webpack-admin-create-staff-profile',
  gulpTaskFn: gulpTaskFn,
  minifyJs: [path.join(base, 'create-profile.js')]
}
