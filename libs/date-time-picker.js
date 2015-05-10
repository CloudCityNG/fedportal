"use strict";

module.exports = function (selector) {
  return $(selector).datetimepicker({
    //viewMode: 'years',
    format: 'DD-MM-YYYY',
    showTodayButton: true
    //startView: 2
  });
};
