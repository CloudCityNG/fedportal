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

$('.publish-edit-trigger').click(function() {
  var $el = $(this).hide();
  $el.prev().prop('disabled', false).prop('checked', false);
  $el.next().show();
});
