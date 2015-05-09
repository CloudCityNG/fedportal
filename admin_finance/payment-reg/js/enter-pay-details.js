$(function () {
  "use strict";

  var
    $amount = $('#amount'),
    $amountText = $('#amount-text'),
    owing = Number($('#amount-owing').text()),
    $receiptNo = $('#receipt-no'),
    $submitBtn = $('#process-pay-details-received-from-student-submit');

  $amountText.on({
    'blur': function () {

      var val = $amountText.val().trim();

      if (val === '') {return;}

      $amountText.val(numberFormat(val));

      $amount.val(val.replace(/,/g, ''));
    },

    'input': function () {
      var val = $amountText.val();

      if (val === '') {return;}

      $amount.val(val.replace(/,/g, ''));
    }
  });

  var $form = $('form').formValidation({
    framework: 'bootstrap',

    icon: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },

    fields: {
      'amount-text': {
        validators: {
          regexp: {
            regexp: /^\d+(?:,\d+)*(?:\.\d*)?$/,
            message: 'Invalid amount'
          }
        }
      }
    }
  });

  $form.on('success.form.fv', function (evt) {
    var receipt = Number($amount.val());

    if (-receipt < owing) {

      var message = "Amount received is more than amount owed.\nReceipt = " +
                    numberFormat(receipt) + "\nOwing = " +
                    numberFormat(owing) + "\n\nClick 'ok' to proceed " +
                    "(in which case the school will be owing student.)\n" +
                    "or click 'cancel' to correct the figure.";

      if (!window.confirm(message)) {
        evt.preventDefault();
      }
    }
  });
});
