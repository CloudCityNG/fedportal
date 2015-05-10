$(function() {
  "use strict";
  var $form = $('#bio-data-form').formValidation();

  var $dateOfBirth = $('#date-of-birth');

  $('.show-date-picker')
    .datepicker({
      format   : 'dd-M-yyyy',
      startView: 2
    })
    .on('changeDate', function(evt) {
          $dateOfBirth.val(moment(evt.date).format('YYYY-MM-DD'));
          $form.formValidation('revalidateField', "student_bio[dateofbirth]");
          $form.formValidation('revalidateField', "date-of-birth-view");
        });
});
