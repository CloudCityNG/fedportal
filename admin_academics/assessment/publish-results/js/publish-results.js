(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({"C:\\wamp\\www\\fedportal\\admin_academics\\assessment\\publish-results\\js\\publish-results-raw.js":[function(require,module,exports){
"use strict";

(function semesterCourseQueryForm() {
  var tenMostRecentSemesters = JSON.parse($('#tenMostRecentSemesters-container').text());

  $('#semester').autocomplete(
    require('./../../../utilities/js/admin-academics-utilities.js').sessionSemesterAutoComplete(
      tenMostRecentSemesters, 'label')
  );

  $('#semester-course-query-form').formValidation(
    {
      fields: {
        'semester-course-query[semester_id]': {
          excluded  : false,
          validators: {
            notEmpty: {message: 'You may only pick from the drop down list'}
          }
        }
      }
    }
  );

})();

},{"./../../../utilities/js/admin-academics-utilities.js":"C:\\wamp\\www\\fedportal\\admin_academics\\utilities\\js\\admin-academics-utilities.js"}],"C:\\wamp\\www\\fedportal\\admin_academics\\utilities\\js\\admin-academics-utilities.js":[function(require,module,exports){
"use strict";

/**
 *
 * @param {Array} source
 * @param {String} fieldToDisplay - the field from the source that will be set as value
 * of form control been auto-completed
 *
 * @returns {{minLength: number, source: Array, select: Function}}
 */
function sessionSemesterAutoComplete(source, fieldToDisplay) {
  return {
    minLength: 1,

    source: source,

    select: function(evt, ui) {
      var
        $el      = $(this),
        $related = $($el.data('related-input-id'));

      $related.val(ui.item.id);

      if (evt.originalEvent.which === 1) {
        window.setTimeout(function() {
                            $el.val(ui.item[fieldToDisplay]);
                          }
        );
      }

      window.setTimeout(function() {
                          $el.closest('form').formValidation('revalidateField', $el);
                          $el.closest('form').formValidation('revalidateField', $related);
                        }
      );

      return false;
    }
  };
}

module.exports = {
  sessionSemesterAutoComplete: sessionSemesterAutoComplete
};

},{}]},{},["C:\\wamp\\www\\fedportal\\admin_academics\\assessment\\publish-results\\js\\publish-results-raw.js","C:\\wamp\\www\\fedportal\\admin_academics\\utilities\\js\\admin-academics-utilities.js"]);
