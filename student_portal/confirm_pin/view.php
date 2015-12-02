<?php
$alert = '';
$pin = '';
$regNo = '';
$email = '';

if (isset($_SESSION['ConfirmStudentRegistrationPinPost'])) {
  $content = json_decode($_SESSION['ConfirmStudentRegistrationPinPost'], true);
  $pin = $content['pin'];
  $regNo = $content['reg_no'];
  $email = $content['email'];

  $alert = "
  <div class='alert alert-dismissible alert-danger' role='alert'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span>
    </button>
    <div>Pin validation or account creation failed| Please try another pin or matriculation number.</div>
  </div>
  ";
}

unset($_SESSION['ConfirmStudentRegistrationPinPost']);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/css/login.min.css', true) ?>"/>
</head>
<body>
  <div class="form-container">
    <?php echo $alert; ?>
    <form id="sign-up-form" role="form" class="form-horizontal well" method="post"
          data-fv-framework="bootstrap"
          data-fv-message="This value is not valid"
          data-fv-icon-valid="glyphicon glyphicon-ok"
          data-fv-icon-invalid="glyphicon glyphicon-remove"
          data-fv-icon-validating="glyphicon glyphicon-refresh">
      <fieldset>
        <legend>Confirm pin and create account</legend>

        <div class="form-group">
          <label class="control-label col-sm-3" for="pin">Pin</label>

          <div class="col-sm-9">
            <input type="text" name="confirm_pin[pin]" class="form-control" required id="pin"
                   value="<?php echo $pin; ?>"
                   data-fv-stringlength="true" data-fv-stringlength-min="8" data-fv-stringlength-message="Invalid Pin">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-3" for="pin-reg_no">Matriculation No.</label>

          <div class="col-sm-9">
            <input type="text" name="confirm_pin[reg_no]" class="form-control" required id="pin-reg_no"
                   value="<?php echo $regNo; ?>"
                   data-fv-stringlength="true" data-fv-stringlength-min="5">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-3" for="email">Email</label>

          <div class="col-sm-9">
            <input type="email" name="confirm_pin[email]" class="form-control" required id="email"
                   value="<?php echo $email; ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-3" for="pin-password">Password</label>

          <div class="col-sm-9">
            <input type="password" name="confirm_pin[password]" class="form-control" required id="pin-password"
                   data-fv-stringlength="true" data-fv-stringlength-min="5">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-3" for="confirm-password">Confirm Password</label>

          <div class="col-sm-9">
            <input type="password" name="confirm_pin[confirm-password]" class="form-control" required
                   id="confirm-password"
                   data-fv-stringlength="true" data-fv-stringlength-min="5" data-fv-identical="true"
                   data-fv-identical-field="confirm_pin[password]"
                   data-fv-identical-message="The password and its confirm are not the same">
          </div>
        </div>
      </fieldset>

      <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
          <button class="btn btn-primary btn-lg btn-block" type="submit">Confirm PIN</button>
        </div>
      </div>
    </form>
    <div style="text-align: right">
      <a href="<?php echo path_to_link(__DIR__ . '/../login') ?>">Login instead</a>
    </div>
  </div>

  <p class="text-center">
    Copyright &copy; <span id="year" class="mr5"></span>
    <span>Federal School of Dental Technology &amp; Therapy Enugu</span>
  </p>
  <script>document.getElementById("year").innerHTML = new Date().getFullYear();</script>

  <?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

  <script>
    $(document).ready(function () {
      $('#sign-up-form').formValidation();
    });
  </script>
</body>
</html>
