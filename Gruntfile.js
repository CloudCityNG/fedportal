module.exports = function(grunt) {
  "use strict";

  /*jshint camelcase: false*/

  require('load-grunt-tasks')(grunt);

  grunt.initConfig({
      browserify: {
        'admin-academics-assessment-publish-scores': {
          options: {
            watch: true,
            keepAlive: true
          },
          files: {
            'admin_academics/assessment/publish-results/js/publish-results.js': [
              'admin_academics/assessment/publish-results/js/publish-results-raw.js',
              'admin_academics/utilities/js/admin-academics-utilities.js'
            ]
          }
        }
      }
    }
  );
};
