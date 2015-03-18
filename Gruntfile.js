/**
 * Created by maneptha on 24-Feb-15.
 */

module.exports = function (grunt) {
  "use strict";

  require('load-grunt-tasks')(grunt);

  var projectBrowserifyAliases = [
    './libs/date-time-picker.js:dateTimePicker',
    './libs/getFormData.js:getFormData'
  ];

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

      admin_academics: { //jshint ignore: line
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
            'libs/jquery-ui-autocomplete.min.js',
            'libs/jquery.slimscroll.min.js',
            'libs/jquery.easing.min.js',
            'libs/underscore.min.js',
            'libs/mustache.min.js',
            'libs/mutation-summary/mutation-summary.js',
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
          //'browserify:admin_academics',
          'uglify:admin_academics'
        ]
      }
    },

    browserify: {
      admin_academics: { // jshint ignore: line
        options: {
          alias: [
            './admin_academics/home/js/session-auto-complete-setting.js:sessionAutoCompleteSetting'
          ]
        },
        files: {
          'admin_academics/academic_session/js/new-session-brow.js': [
            'admin_academics/academic_session/js/new-academic-session.js'
          ]
        }
      },

      'edu_history': {
        options: {
          alias: projectBrowserifyAliases
        },
        files: {
          'student_portal/edu_history/js/scripts.js': ['student_portal/edu_history/js/scripts-raw.js']
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
            'libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.css',
            'libs/css/jquery-ui-autocomplete.min.css',
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
  });

  grunt.registerTask('dist-js', 'uglify');
  grunt.registerTask('initial', ['cssmin:combine', 'cssmin:initial', 'concat:initial', 'uglify:initial']);
};
