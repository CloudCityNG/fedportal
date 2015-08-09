"use strict";

var gulp = require('gulp')
var plugins = require('gulp-load-plugins')();
var browserSync = require('browser-sync').create()

var adminAcademics = require('./admin_academics/all.js')
var assessment = adminAcademics.assessment
var gradeStudent = assessment.gradeStudent

var compressScripts = []

gulp.task(gradeStudent.gulpTaskName, gradeStudent.gulpTaskFn(gulp, plugins))

gulp.task('webpack', [gradeStudent.gulpTaskName])

gulp.task('initial-js', function() {

  return gulp.src([
    'libs/jquery/dist/jquery.js',
    'libs/moment/min/moment.min.js',
    'libs/bootstrap/dist/js/bootstrap.js',
    'libs/jquery-ui.js',
    'libs/jquery.slimscroll.min.js',
    'libs/jquery.easing.min.js',
    'libs/underscore.min.js',
    'libs/mustache.min.js',
    'libs/formValidation/js/formValidation.js',
    'libs/formValidation/js/framework/bootstrap.js',
    'libs/ajax-alert/dist/js/ajax-alert.min.js',
    'libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.js',
    'libs/appear/jquery.appear.js',
    'libs/jquery.placeholder.js',
    'libs/fastclick.js',
    'libs/offscreen.js',
    'libs/main.js',
    'libs/bootstrap-validator.js',
    'libs/number-format.js',
    'libs/js/jquery.cookie.js',
    'libs/js/jquery.treeview.js'
  ])
    .pipe(plugins.concat('compiled.js'))
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.uglify())
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(gulp.dest('./libs'))
})

gulp.task('initial-css', function() {
  return gulp.src([
    'libs/bootstrap/dist/css/bootstrap.css',
    'libs/css/themify-icons.css',
    'libs/css/animate.min.css',
    'libs/formValidation/css/formValidation.min.css',
    'libs/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
    'libs/css/jquery-ui.css',
    'libs/css/jquery.treeview.css',
    'libs/css/skins/palette.css'
  ])
    .pipe(plugins.concat('compiled.css'))
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.minifyCss())
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(gulp.dest('./libs/css'))
})

gulp.task('initial', ['initial-js', 'initial-css'])

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
