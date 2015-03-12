/**
 * Created by maneptha on 01-Mar-15.
 */

"use strict";

module.exports = function (selector) {
  return $(selector).datetimepicker({
    //viewMode: 'years',
    format: 'DD-MM-YYYY',
    showTodayButton: true
    //startView: 2
  });
};
