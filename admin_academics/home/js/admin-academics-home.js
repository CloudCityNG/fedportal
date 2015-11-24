$(function () {
  "use strict";

  $('.side-bar-navs').kmNavigator()

  $('.show-date-picker').datepicker({
    format: 'dd-mm-yyyy'
  })
    .on('changeDate', function (evt) {
      var $target = $(evt.target);
      $target.closest('form').bootstrapValidator(
        'revalidateField', $target.children('.form-control').prop('name')
      );
    });

});
