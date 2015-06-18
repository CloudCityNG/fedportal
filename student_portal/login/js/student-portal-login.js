$(function () {
  "use strict";

  var $regNo = $('#reg_no'),
      $password = $('#password'),
      $pinRegNo = $('#pin-reg_no'),
      $signUp = $('.sign-up-form').formValidation(),
      $signIn = $('#sign-in-form').formValidation(),
      $signInContainer = $('.sign-in-container'),
      $signUpContainer = $('.sign-up-container'),
      $alert = $('.alert-container').ajaxAlert(),
      $logInInstead = $('#log-in-instead'),
      $formBtns = $('form button[type=submit],form input[type=submit]');

  (function signInFormController() {
    $signIn.on('success.form.fv', function (evt) {
      evt.preventDefault();

      $alert.ajaxAlert('show', "Authenticating......please wait.", 'success');

      $.ajax({
        url: '',
        type: 'post',
        data: {
          auth: true, regNo: $regNo.val().trim(), password: $password.val().trim()
        },

        success: function (data) {
          if (data.auth === true) {
            $alert.ajaxAlert('show', "Authentication successful. Redirecting......" +
                                     "please wait.", 'success');
            window.location.href = STATIC_ROOT + 'student_portal/home/';

          } else {
            $alert.ajaxAlert('show',
              'Invalid username or password! Click on "New user sign up"' +
              ' if you don\'t have an account.',

              'danger'
            );
          }
        },

        error: function (xhr) {
          console.log('error', xhr.responseText);
        }
      });

      $signIn.data('formValidation').resetForm();

    });
  })();

  (function signUpFormController() {

    $signUp.on('success.form.fv', function (evt) {
      evt.preventDefault();
      $alert.ajaxAlert('show', "Validating pin.......please wait!", 'success');

      $.ajax({
        url: '',
        type: 'post',
        data: $signUp.serialize(),

        success: function (data) {
          if (data.confirmed) {
            $regNo.val($pinRegNo.val());
            $logInInstead.trigger('click', '#reg_no');

            setTimeout(function () {
              $alert.ajaxAlert('show', "Pin validation successful. Please login", 'success');
            }, 800);

          } else {
            $alert.ajaxAlert('show', "Pin validation failed. Please try another pin", 'danger');
          }

        },

        error: function (xhr) {
          console.log(xhr);
        }
      });

      $signUp.data('formValidation').resetForm();
    });
  })();

  $('#sign-up-btn').click(function () {
    resetForm();

    $signInContainer.fadeOut(500, function () {
      $signUpContainer.fadeIn(500);
    });

  });

  $logInInstead.click(function (evt, noResetSelector) {
    resetForm(noResetSelector);

    $signUpContainer.fadeOut(500, function () {
      $signInContainer.fadeIn(500);
    });

  });

  function resetForm(noResetSelector) {
    $alert.ajaxAlert('reset');

    $('.form-control').not(noResetSelector).each(function () {
      $(this).val('');
    });

    $signIn.data('formValidation').resetForm();
    $signUp.data('formValidation').resetForm();
  }
});
