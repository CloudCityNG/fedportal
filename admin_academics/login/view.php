<?php
$alert = '';
$alertClass = 'alert-danger';
if ($adminLoginContext) {
  $message = "<ul>\n";

  foreach ($adminLoginContext['messages'] as $messageText) {
    $message .= "  <li>{$messageText}</li>\n";
  }

  $message .= "</ul>";

  $alert = "
  <div class='alert alert-dismissible {$alertClass}' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>

        <h5>{$adminLoginContext['status']}</h5>

        <div>
          {$message}
        </div>
  </div>
  ";
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include(__DIR__ . '/../../includes/header.php'); ?>

  <link rel="stylesheet"
        href="<?php echo path_to_link(__DIR__ . '/css/login.min.css', true) ?>"/>
</head>
<body>
  <div class="form-container">
    <?php echo $alert; ?>

    <form method="post" class="well form-horizontal" role="form" novalidate id="admin-login-form"
          data-fv-framework="bootstrap"
          data-fv-icon-valid="glyphicon glyphicon-ok"
          data-fv-icon-invalid="glyphicon glyphicon-remove"
          data-fv-icon-validating="glyphicon glyphicon-refresh">
      <fieldset>
        <legend>Admin Login</legend>

        <div class="form-group">
          <label for="username" class="control-label col-sm-4">Username</label>

          <div class="col-sm-8">
            <input type="text" class="form-control" name="username" id="username" required minlength="3">
          </div>
        </div>

        <div class="form-group">
          <label for="password" class="control-label col-sm-4">Password</label>

          <div class="col-sm-8">
            <input type="password" class="form-control" name="password" id="password" required minlength="6">
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-8 col-sm-offset-4">
            <button type="submit" class="btn" role="button" name="submit_btn">Login</button>
          </div>
        </div>
      </fieldset>
    </form>
  </div>

  <p class="text-center">
    Copyright &copy;
    <span id="year" class="mr5"></span>
    <span>Federal School of Dental Technology &amp; Therapy Enugu</span>
  </p>

  <?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

  <script>document.getElementById("year").innerHTML = new Date().getFullYear();</script>

  <script>
    $(document).ready(function () {
      $('#admin-login-form').formValidation();
    });
  </script>
</body>
</html>
