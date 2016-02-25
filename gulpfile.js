"use strict";
/*jshint camelcase:false*/

var gulp = require('gulp')
var plugins = require('gulp-load-plugins')();

var adminAcademics = require('./admin_academics/all.js')
var assessment = adminAcademics.assessment
var gradeStudent = assessment.gradeStudent
var semester = adminAcademics.semester

var staffProfile = adminAcademics.staffProfile
var createStaffProfile = staffProfile.createProfile

var courses = adminAcademics.courses
var createCourse = courses.createCourse

var studentPortal = require('./student_portal/all.js')
var eduHistory = studentPortal.edu_history

var compressScripts = [
  './admin_academics/home/js/admin-academics-home.js',
  './admin_academics/login/js/login.js',
  './student_portal/bio_data1/js/bio-data.js',
  './student_portal/home1/js/home.js'
].concat(semester.minifyJs)
  .concat(gradeStudent.minifyJs)
  .concat(createStaffProfile.minifyJs)
  .concat(createCourse.minifyJs)
  .concat(eduHistory.minifyJs)

var lessFiles = [
  './admin_academics/**/*.less',
  './student_portal/**/*.less',
  'libs/navigation-control/navigation-control.less',
]

gulp.task(gradeStudent.gulpTaskName, gradeStudent.gulpTaskFn(gulp, plugins))
gulp.task(semester.gulpTaskName, semester.gulpTaskFn(gulp, plugins))
gulp.task(createStaffProfile.gulpTaskName, createStaffProfile.gulpTaskFn(gulp, plugins))
gulp.task(createCourse.gulpTaskName, createCourse.gulpTaskFn(gulp, plugins))
gulp.task(eduHistory.gulpTaskName, eduHistory.gulpTaskFn(gulp, plugins))

gulp.task('webpack', [
  gradeStudent.gulpTaskName,
  semester.gulpTaskName,
  createStaffProfile.gulpTaskName,
  createCourse.gulpTaskName,
  eduHistory.gulpTaskName,
])

gulp.task('initial-js', function () {
  return gulp.src('bower_components/jquery/dist/jquery.js')
    .pipe(plugins.addSrc.append('bower_components/moment/moment.js'))
    .pipe(plugins.addSrc.append('bower_components/bootstrap/dist/js/bootstrap.js'))
    .pipe(plugins.addSrc.append('libs/jquery-ui.js'))
    .pipe(plugins.addSrc.append('libs/jquery.slimscroll.min.js'))
    .pipe(plugins.addSrc.append('libs/jquery.easing.min.js'))
    .pipe(plugins.addSrc.append('libs/underscore.min.js'))
    .pipe(plugins.addSrc.append('libs/mustache.min.js'))
    .pipe(plugins.addSrc.append('bower_components/formvalidation/dist/js/formValidation.js'))
    .pipe(plugins.addSrc.append('bower_components/formvalidation/dist/js/framework/bootstrap.js'))
    .pipe(plugins.addSrc.append('libs/ajax-alert/dist/js/ajax-alert.min.js'))
    .pipe(plugins.addSrc.append('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js'))
    .pipe(plugins.addSrc.append('libs/appear/jquery.appear.js'))
    .pipe(plugins.addSrc.append('libs/jquery.placeholder.js'))
    .pipe(plugins.addSrc.append('libs/fastclick.js'))
    .pipe(plugins.addSrc.append('libs/offscreen.js'))
    .pipe(plugins.addSrc.append('libs/main.js'))
    .pipe(plugins.addSrc.append('libs/bootstrap-validator.js'))
    .pipe(plugins.addSrc.append('libs/number-format.js'))
    .pipe(plugins.addSrc.append('libs/navigation-control/navigation-control.js'))
    .pipe(plugins.addSrc.append('libs/js/jquery.cookie.js'))
    .pipe(plugins.addSrc.append('bower_components/jquery-treeview/jquery.treeview.js'))
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.concat('compiled.js'))
    .pipe(plugins.uglify())
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(gulp.dest('./libs'))
})

var initialJsReload = ['libs/navigation-control/navigation-control.js']
var initialCssReload = ['libs/navigation-control/navigation-control.min.css']

gulp.task('initial-css', function () {
  return gulp.src([
      'bower_components/bootstrap/dist/css/bootstrap.css',
      'libs/css/themify-icons.css',
      'libs/css/animate.min.css',
      'bower_components/formvalidation/css/formValidation.css',
      'bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
      'libs/css/jquery-ui.css',
      'libs/css/jquery.treeview.css',
      'libs/css/skins/palette.css',
      'libs/navigation-control/navigation-control.min.css',
    ])
    .pipe(plugins.concat('compiled.css'))
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.minifyCss())
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(gulp.dest('./libs/css'))
})

gulp.task('initial', ['initial-js', 'initial-css'])

gulp.task('compress-js', function () {
  gulp.src(compressScripts, {base: '.'})
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.uglify())
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(gulp.dest('.'))
})

gulp.task('less', function () {
  gulp.src(lessFiles, {base: '.'})
    .pipe(plugins.less())
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.minifyCss())
    .pipe(plugins.rename({suffix: '.min', extname: '.css'}))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(gulp.dest('.'))
})

gulp.task('watch', function () {
  gulp.watch(compressScripts, ['compress-js'])
  gulp.watch(lessFiles, ['less'])
  gulp.watch(initialJsReload, ['initial-js'])
  gulp.watch(initialCssReload, ['initial-css'])
})

gulp.task('default', ['initial', 'watch', 'webpack'])
