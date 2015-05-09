"use strict";

var
  thisYear = new Date().getFullYear(),
  lastYear = thisYear - 1;

var sessionValidation = {
  callback: function (value) {
    var regExp = /^(\d{4})\/(\d{4})$/.exec(value);

    if (regExp) {
      var
        start = parseInt(regExp[1]),
        end = parseInt(regExp[2]);

      if ((end - start) === 1) {
        var
          thisYear = new Date().getFullYear(),
          startDiff = Math.abs(start - thisYear),
          endDiff = Math.abs(end - thisYear);

        return startDiff < 3 && endDiff < 3;
      }
    }
    return false;
  },

  message: 'Please match pattern e.g ' + lastYear + '/' + thisYear
};

$(document.body).on({
  'click': function () {

    var
      $el = $(this),
      $fieldSet = $el.closest('.current-session-panel').find('fieldset'),
      $formControls = $fieldSet.find('.form-control'),
      $semesterBtn = $('.current-session-form-btn');

    $fieldSet.closest('.current-session-form').data('formValidation').resetForm();

    if ($el.is('.glyphicon-edit')) {
      $formControls.each(function () {
        $(this).prop('disabled', false);
      });

      $el
        .removeClass('glyphicon-edit')
        .addClass('glyphicon-eye-open')
        .attr('title', 'View only');

      $semesterBtn.show();

    } else {

      $formControls.each(function () {
        $(this).prop('disabled', true);
      });

      $el
        .removeClass('glyphicon-eye-open')
        .addClass('glyphicon-edit')
        .attr('title', 'Edit current session');

      $semesterBtn.hide();
    }
  }
}, '.current-session-edit-trigger');

(function currentSessionForm() {
  $('.current-session-form').formValidation({
    framework: 'bootstrap',

    icon: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },

    fields: {
      session: {
        validators: {
          callback: {
            callback: sessionValidation.callback,
            message: sessionValidation.message
          }
        }
      }
    }
  });
})();

(function newSessionForm() {
  $('.new-session-form').formValidation({
    framework: 'bootstrap',

    icon: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
    },

    fields: {

      'new_session[session]': {
        validators: {
          callback: {
            callback: sessionValidation.callback,
            message: sessionValidation.message
          }
        }
      }
    }
  });
})();
