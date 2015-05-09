"use strict";

var
  $alert                     = $('.alert-container').ajaxAlert(),
  sessionValidationCb        = require('sessionValidationCb'),
  sessionAutoCompleteSetting = require('sessionAutoCompleteSetting'),
  getFormData                = require('getFormData');

new MutationSummary({
  callback: semesterObserver,

  queries: [{
    'element': '.semester-form'
  }]
});

$(document.body).on({
  'click': function () {
    var $el = $(this),
        $fieldSet = $el.closest('.current-semester-panel').find('fieldset'),
        $formControls = $fieldSet.find('.form-control').not('#session'),
        $semesterBtn = $('.current-semester-form-btn');

    $fieldSet.closest('.current-semester-form').data('formValidation').resetForm();

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
        .attr('title', 'Edit semester');

      $semesterBtn.hide();
    }
  }
}, '.current-semester-edit-trigger');

function semesterObserver() {
  var
    sessionsAutoComplete = {},
    $semesterLink = $('#semester.link'),
    semesterLinkContext = $semesterLink.data('template.context'),
    url = $semesterLink.data('template-url');

  if (_.isObject(semesterLinkContext)) {
    sessionsAutoComplete = semesterLinkContext.sessions;
  }

  $('.semester-session').autocomplete(
    sessionAutoCompleteSetting(sessionsAutoComplete)
  );

  (function datePicker() {

    var $datetimePicker = require('dateTimePicker')('.show-date-picker');

    $datetimePicker.on('dp.change dp.show', function () {
      var $el = $(this);
      $el.closest('form').formValidation('revalidateField', $el.find('input'));
    });
  })();

  (function currentSemesterForm() {

    var $form = $('.current-semester-form').formValidation({
      framework: 'bootstrap',

      icon: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },

      fields: {
        start_date: { // jshint ignore:line
          validators: {
            date: {format: 'DD-MM-YYYY'}
          }
        },

        end_date: { // jshint ignore:line
          validators: {
            date: {format: 'DD-MM-YYYY'}
          }
        }
      }
    });

    $form.on('success.form.fv', function (evt) {
      evt.preventDefault();

      $.ajax({
        url: STATIC_ROOT + 'admin_academics/semester/ ',
        type: 'post',
        data: $form.serialize(),

        success: function (data) {
          console.log(data);
        },

        error: function (xhr) {
          console.log(xhr.responseText);
        }
      });
    });
  })();

  (function newSemesterForm() {
    var $form = $('.new-semester-form').formValidation({
      framework: 'bootstrap',

      icon: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },

      fields: {
        start_date: { // jshint ignore:line
          validators: {
            date: {format: 'DD-MM-YYYY'}
          }
        },

        end_date: { // jshint ignore:line
          validators: {
            date: {format: 'DD-MM-YYYY'}
          }
        },

        session: {
          validators: {
            callback: {
              callback: sessionValidationCb,
              message: 'You may only pick from the drop down list'
            }
          }
        },

        'session_id': {
          excluded: false,
          validators: {
            notEmpty: {message: 'You may only pick from the drop down list'}
          }
        }
      }
    });

    $form.on('err.field.fv', '#new-semester-session', function (evt) {
      var relatedValueSelector = $(evt.target).data('related-value');
      $(relatedValueSelector).val('');
    });

    $form.on('success.form.fv', function (evt) {
      $alert.ajaxAlert('show', 'Creating new semester. Please wait!', 'success');
      evt.preventDefault();

      $.ajax({
        url: url,

        type: 'POST',

        data: {newSemester: true, data: getFormData($form)},

        success: function (data) {
          if (data.invalidPostData) {

            $alert.ajaxAlert(
              'show',
              'Your form data is not valid. Please check your input! New semester not created.',
              'danger');

          } else if (data.created) {
            $alert.ajaxAlert('show', 'New semester successfully created!', 'success');

          } else {
            $alert.ajaxAlert('show', 'Error! New semester not created.', 'danger');
          }

        },

        error: function (xhr) {
          $alert.ajaxAlert('show', 'Error! New semester not created.', 'danger');
          console.log(xhr.responseText);
        }
      });

      $form.data('formValidation').resetForm();

    });
  })();
}
