<!doctype html>
<html class="signin no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>

  <link rel="stylesheet"
        href="<?php echo STATIC_ROOT . 'admin_academics/login/css/login.css' ?>"/>
</head>

<body class="bg-primary">

<div class="zoomOutUp"></div>

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

      <div class="row sign-in-container">
        <form class="form-inline sign-in-form col-sm-8" id="sign-in-form" data-toggle="validator">
          <span class="legend">Academic Admin Login</span>

          <div class="form-group">
            <label class="sr-only" for="password"></label>

            <input name="password" type="password" class="form-control" id="password"
                   required placeholder="Password">

            <div class="help-block with-errors"></div>
          </div>

          <div class="form-group">
            <button class="btn btn-primary" type="submit"
                    style="background-color: #555; border-radius: 5px;">
              Sign in
            </button>

            <div class="help-block with-errors"></div>
          </div>
        </form>
      </div>

      <p class="text-center">
        Copyright &copy;
        <span id="year" class="mr5"></span>
        <span>Federal School of Dental Technology &amp; Therapy Enugu</span>
      </p>

  </div>
</div>

<?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

<script>
  document.getElementById("year").innerHTML = new Date().getFullYear();
</script>

<script src="<?php echo STATIC_ROOT . 'admin_academics/login/js/login.js' ?>"></script>
</body>

</html>
