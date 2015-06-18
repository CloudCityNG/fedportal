<!doctype html>
<html class="signin no-js" lang="">
<head>
  <?php include_once(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/../../libs/css/main.min.css', true) ?>">

  <style>
    .panel {
      max-width: 800px;
      margin: auto;
      padding: 30px;
    }

    .alert-container {
      min-height: 85px;
    }

    .forms {
      min-height: 420px;
    }

    .sign-in-form {
      position: relative;
    }

    .legend {
      position: absolute;
      left: 24px;
      top: -25px;
      font-size: larger;
    }

    .sign-in-container .help-block.with-errors {
      min-height: 25px;
      color: #FF8E8E;
    }

    .sign-up-form {
      min-height: 20px;
      padding: 19px;
      margin-bottom: 20px;
      background-color: #ffffff;
      border: 1px solid #ffffff;
      border-radius: 4px;
      box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .sign-up-container {
      margin-bottom: 50px;
    }

  </style>
</head>

<body class="bg-primary">

  <div class="overlay bg-primary"></div>

  <div class="center-wrapper">
    <div class="center-content">

      <section class="panel" style="background-color: inherit">
        <div class="alert-container"></div>

        <script type="text/template" id="alert-template">
          <div class="alert alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>

            <span class="alert-message"></span>
          </div>
        </script>

        <div class="forms">
          <div class="row sign-in-container">
            <form class="form-inline sign-in-form col-sm-9" id="sign-in-form"
                  data-fv-framework="bootstrap"
                  data-fv-message="This value is not valid"
                  data-fv-icon-valid="glyphicon glyphicon-ok"
                  data-fv-icon-invalid="glyphicon glyphicon-remove"
                  data-fv-icon-validating="glyphicon glyphicon-refresh">
              <span class="legend">Existing User Login</span>

              <div class="form-group">
                <label class="sr-only" for="reg_no">Registration Number</label>

                <input type="text" id="reg_no" name="reg_no"
                       class="form-control" placeholder="Matric No" required>
              </div>

              <div class="form-group">
                <label class="sr-only" for="password">Password</label>

                <input name="password" type="password" class="form-control" id="password"
                       required placeholder="Password">
              </div>

              <div class="form-group">
                <button class="btn btn-primary" type="submit"
                        style="background-color: #555; border-radius: 5px;">
                  Sign in
                </button>
              </div>
            </form>

            <div class="col-sm-offset-1 col-sm-2">
              <button class="btn btn-default" id="sign-up-btn">New user sign up</button>
            </div>
          </div>

          <div class="sign-up-container" style="display: none; ">
            <form class="sign-up-form" role="form"
                  data-fv-framework="bootstrap"
                  data-fv-message="This value is not valid"
                  data-fv-icon-valid="glyphicon glyphicon-ok"
                  data-fv-icon-invalid="glyphicon glyphicon-remove"
                  data-fv-icon-validating="glyphicon glyphicon-refresh">
              <fieldset>
                <legend>Confirm pin and create account</legend>

                <div class="form-group">
                  <label class="sr-only" for="pin">Pin</label>

                  <div>
                    <input type="text" name="confirm_pin[pin]" class="form-control"
                           required id="pin"
                           data-fv-stringlength="true"
                           data-fv-stringlength-min="8"
                           data-fv-stringlength-message="Invalid Pin"
                           placeholder="PIN">
                  </div>
                </div>

                <div class="form-group">
                  <label class="sr-only" for="pin-reg_no">Registration No.</label>

                  <div>
                    <input type="text" name="confirm_pin[reg_no]" class="form-control"
                           required id="pin-reg_no"
                           data-fv-stringlength="true"
                           data-fv-stringlength-min="5"
                           placeholder="Matric No">
                  </div>
                </div>

                <div class="form-group">
                  <label class="sr-only" for="email">Email</label>

                  <div>
                    <input type="email" name="confirm_pin[email]" class="form-control"
                           required id="email"
                           placeholder="Email address">
                  </div>
                </div>

                <div class="form-group">
                  <label class="sr-only" for="pin-password">Password</label>

                  <div>
                    <input type="password" name="confirm_pin[password]" class="form-control"
                           required id="pin-password"
                           placeholder="Password"
                           data-fv-stringlength="true"
                           data-fv-stringlength-min="5">
                  </div>
                </div>

                <div class="form-group">
                  <label class="sr-only" for="confirm-password">Confirm Password</label>

                  <div>
                    <input type="password" name="confirm_pin[confirm-password]" class="form-control"
                           required id="confirm-password"
                           placeholder="Confirm Password"
                           data-fv-stringlength="true"
                           data-fv-stringlength-min="5"
                           data-fv-identical="true"
                           data-fv-identical-field="confirm_pin[password]"
                           data-fv-identical-message="The password and its confirm are not the same">
                  </div>
                </div>
              </fieldset>

              <button class="btn btn-primary btn-lg btn-block" type="submit">Confirm PIN</button>
            </form>

            <div class="pull-right">
              <a class="btn btn-default btn-lg" id="log-in-instead">Let me login instead</a>
            </div>

            <div class="clearfix"></div>
          </div>
        </div>

        <p class="text-center copyright">
          Copyright &copy; <span id="year" class="mr5"></span>
          <span>Federal School of Dental Technology &amp; Therapy Enugu</span>
        </p>
      </section>

    </div>
  </div>

  <script>
    document.getElementById('year').innerText = new Date().getFullYear();
  </script>

  <?php include_once(__DIR__ . '/.././../includes/js-footer.php'); ?>

  <script src="<?php echo path_to_link(__DIR__ . '/js/student-portal-login.min.js', true) ?>"></script>
</body>

</html>
