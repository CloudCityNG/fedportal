$(function () {
  "use strict";

  var
    $fee = $('#fee'),
    $feeText = $('#fee-text');

  $feeText.on({
    'blur': function () {

      var val = $feeText.val().trim();

      if (val === '') {return;}

      $feeText.val(numberFormat(val));

      $fee.val(val.replace(/,/g, ''));
    },

    'input': function () {
      var val = $feeText.val();

      $fee.val(val.replace(/,/g, ''));
    }
  });

  var $form = $('#define-fees-form').formValidation({
    framework: 'bootstrap',

    icon: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },

    fields: {
      'fee-text': {
        validators: {
          regexp: {
            regexp: /^\d+(?:,\d+)*(?:\.\d*)?$/,
            message: 'Invalid amount'
          }
        }
      }
    }
  });

  (function levelsAutoComplete() {
    var levels = [
      'OND1',
      'OND2',
      'HND1',
      'HND2'
    ];

    $('#level').autocomplete({
      source: levels
    });
  })();

});
