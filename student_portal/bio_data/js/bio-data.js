$(function () {
  "use strict";
  var $form = $('#bio-data-form').formValidation();

  $('.show-date-picker')
    .datepicker({
      format: 'dd-mm-yyyy',
      startView: 2
    })
    .on('changeDate', function () {
      $form.formValidation('revalidateField', "student_bio[dateofbirth]");
    });
});
