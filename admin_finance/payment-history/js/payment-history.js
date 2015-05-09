$(function () {
  "use strict";

  var
    templateToRender = '',
    paymentHistoryUrl = STATIC_ROOT + '/admin_finance/payment-history/',
    paymentHistoryTmpl = STATIC_ROOT +
                         '/admin_finance/payment-history/payment-history-ajax-response-table-display.mustache';

  $.get(paymentHistoryTmpl, function (template) {
    Mustache.parse(template);
    templateToRender = template;
  });

  var $regNoFormGrp = $('.reg-no-form-group'),
      $regNo = $('#reg_no'),
      $historyContainer = $('.payment-history-data-container');

  $('form').validator({delay: 0, disable: true}).submit(function (evt) {
    var regNo = $regNo.val().trim();

    if ($regNo.data('bs.validator.errors').length) {
      $historyContainer.html('').hide();
      return;
    }

    evt.preventDefault();

    $.ajax({
      url: paymentHistoryUrl,

      type: 'post',

      data: {'reg_no': regNo},

      success: ajaxSuccess,

      error: function (xhr) {
        $historyContainer.html(xhr.responseText).show();
      }
    });
  });

  function ajaxSuccess(data) {
    if (!data.found) {
      $historyContainer.html('').hide();

      $regNoFormGrp
        .removeClass('has-success')
        .addClass('has-error')
        .find('.with-errors.help-block')
        .text('Unknown student registration number: ' + data.regNo);

    } else {

      var idx = 0;

      data.idx = function () {
        return ++idx;
      };

      data.formatAmount = function () {

        return function (text, render) {
          return numberFormat(render(text));
        };

      };

      var rendered = Mustache.render(templateToRender, data);
      $historyContainer.html(rendered).show();

    }
  }

});
