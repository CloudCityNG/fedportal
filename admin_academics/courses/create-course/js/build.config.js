"use strict";

var path = require('path')
var deepCopy = require('deepcopy')
var rootConfig = deepCopy(require(path.join('..', '..', '..', '..', 'root.config.js')))

var base = __dirname

var entry = path.join(base, 'create-course-raw.js')

var webpackConfig = rootConfig.webpackConfig

webpackConfig.entry = entry

webpackConfig.output = {
  base: base,
  filename: 'create-course.js'
}

function gulpTaskFn(gulp, plugins) {
  //console.log('\n\n\n\n\n\nwebpack-admin-create-course', '\n\n\n\n\n\n\n', rootConfig)
  return function () {
    return gulp.src(entry)
      .pipe(plugins.webpack(webpackConfig, require('webpack')))
      .pipe(gulp.dest(base))
  }
}

module.exports = {
  gulpTaskName: 'webpack-admin-create-course',
  gulpTaskFn: gulpTaskFn,
  minifyJs: [path.join(base, 'create-course.js')]
}
