$(function () {
  "use strict";

  var $password = $('#password'),
      $signIn = $('#sign-in-form'),
      $alertTemplate = $('#alert-template'),
      $alertContainer = $('.alert-container'),
      url = STATIC_ROOT + 'admin_finance/index.php';

  $signIn.submit(function (evt) {
    resetAlert();
    evt.preventDefault();

    var val = $password.val().trim();

    if (val) {
      showAlert("Authenticating......please wait.", 'success');
      $.ajax({
        url: url,

        type: 'post',

        data: {password: val},

        success: function (data) {
          resetAlert();

          if (data.auth === true) {
            showAlert("Authentication successful. Redirecting......" +
                      "please wait.", 'success');
            window.location.href = STATIC_ROOT + 'admin_finance/admin_fin_dashboard.php';

          } else {
            showAlert(
              'Invalid login credentials!', 'danger'
            );
          }
        },

        error: function (xhr) {
          console.log('error', xhr.responseText);
        }
      });
    }

  });

  function resetAlert() {
    $alertContainer.html('');
  }

  function showAlert(message, contextualClass) {
    var $alert = $($alertTemplate.text());
    $alert
      .attr('class', 'alert alert-dismissible alert-' + contextualClass)
      .find('.alert-message').text(message);

    $alertContainer.append($alert);
  }

});
