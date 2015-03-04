/**
 * Created by maneptha on 18-Feb-15.
 */

$(function () {
  "use strict";

  if (window.File && window.FileReader && window.FileList && window.Blob) {
    // Great success! All the File APIs are supported.
  } else {
    window.alert('The File APIs are not fully supported in this browser.');
  }

  var $error = $('<div class="help-block with-errors"></div>');

  $('.form-control').each(function () {
    $(this).closest('.form-group').append($error.clone());
  });

  $('#photo').change(function (evt) {
    var files = evt.target.files;

    if (files.length) {
      var file = files[0],
          $el = $(this);

      if ((file.type.indexOf('image') === -1) || (file.size > 50 * 1024)) {
        $el.closest('.form-group').append(
          $('<div class="image-error" style="color:#DA3E16">You may only select an image file with size not exceeding 50kb.</div>')
        );
        $el.css('border-color', 'red');

      } else {
        $('.image-error').remove();
        $el.css('border-color', '');
      }
    } else {

      $('.image-error').remove();
    }
  });
});
