"use strict";

var gulp = require('gulp')
var plugins = require('gulp-load-plugins')();
var browserSync = require('browser-sync').create()

var adminAcademics = require('./admin_academics')
var assessment = adminAcademics.assessment
var gradeStudent = assessment.gradeStudent

var compressScripts = []

gulp.task(gradeStudent.gulpTaskName, gradeStudent.gulpTaskFn(gulp, plugins))

gulp.task('webpack', [gradeStudent.gulpTaskName])

gulp.task('compress-js', function() {
  gulp.src(compressScripts, {base: '.'})
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.uglify())
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(gulp.dest('.'))
})

gulp.task('watch', function() {
  gulp.watch(compressScripts, ['compress-js'])
})

gulp.task('browser-sync', function() {
  browserSync.init({
    files: ['./**/*.js', './**/*.css', './**/*.html', './**/*.php'],

    proxy: 'localhost/fedportal'
  })
})
