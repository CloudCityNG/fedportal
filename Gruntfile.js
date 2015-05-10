module.exports = function(grunt) {
  "use strict";

  /*jshint camelcase: false*/

  require('load-grunt-tasks')(grunt);

  grunt.initConfig({
      uglify: {
        initial: {
          options: {
            sourceMap: true
          },
          files: {
            'libs/compiled.min.js': ['libs/compiled-temp.js']
          }
        },

        admin_academics: {
          options: {
            sourceMap: true
          },
          files: {
            'admin_academics/academic_session/js/session.min.js': 'admin_academics/academic_session/js/session.js',
            'admin_academics/home/js/home.min.js': 'admin_academics/home/js/home.js',
            'admin_academics/semester/js/semester.min.js': 'admin_academics/semester/js/semester.js'
          }
        }
      },

      concat: {
        initial: {
          options: {separator: ';\n'},
          files: {
            'libs/compiled-temp.js': [
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
              'libs/number-format.js'
            ]
          }

        },

        'admin_academics': {
          options: {separator: ';'},
          files: {
            'admin_academics/home/js/home-bundle.js': [
              'admin_academics/home/js/home.js',
              'admin_academics/semester/js/*.js',
              'admin_academics/academic_session/js/*.js'
            ]
          }
        }
      },

      watch: {
        'admin_academics': {
          options: {
            livereload: true
          },

          files: [
            'admin_academics/home/js/home.js',
            'admin_academics/semester/js/semester.js',
            'admin_academics/academic_session/js/session.js',
            'admin_academics/courses/*'
          ],

          tasks: [
            'uglify:admin_academics'
          ]
        }
      },

      browserify: {
        'admin-academics-assessment-grade-student': {
          options: {
            watch: true,
            keepAlive: true
          },
          files: {
            'admin_academics/assessment/grade-student/js/grade-student.js': [
              'admin_academics/assessment/grade-student/js/grade-student-raw.js',
              'admin_academics/utilities/js/admin-academics-utilities.js'
            ]
          }
        },

        'admin-academics-semester': {
          options: {
            watch: true,
            keepAlive: true
          },
          files: {
            'admin_academics/semester/js/semester.js': [
              'admin_academics/semester/js/semester-raw.js',
              'admin_academics/utilities/js/admin-academics-utilities.js'
            ]
          }
        }
      },

      clean: {
        deploy: {
          src: ['./../deploy/*']
        }
      },

      cssmin: {
        combine: {
          options: {
            shorthandCompacting: false,
            roundingPrecision: -1
          },
          files: {
            'libs/css/compiled.css': [
              'libs/bootstrap/dist/css/bootstrap.css',
              'libs/css/themify-icons.css',
              'libs/css/animate.min.css',
              'libs/formValidation/css/formValidation.min.css',
              'libs/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
              'libs/css/jquery-ui.css',
              'libs/css/skins/palette.css'
            ]
          }
        },
        initial: {
          options: {
            sourceMap: true, s0: true
          },
          files: [{
            expand: true,
            src: ['libs/css/compiled.css'],
            ext: '.min.css'
          }]
        }
      }
    }
  );

  grunt.registerTask('dist-js', 'uglify');
  grunt.registerTask('initial', ['cssmin:combine', 'cssmin:initial', 'concat:initial', 'uglify:initial']);
};
