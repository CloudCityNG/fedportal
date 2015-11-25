<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include(__DIR__ . '/../../includes/header.php'); ?>

  <link rel="stylesheet"
        href="<?php echo path_to_link(__DIR__ . '/css/login.min.css', true) ?>"/>

  <title>Admin General Login</title>
</head>
<body>
  <form method="post" class="well form-horizontal" role="form">
    <div class="form-group">
      <label for="username" class="control-label col-sm-4">Username</label>

      <div class="col-sm-8">
        <input type="text" class="form-control" name="username" id="username">
      </div>
    </div>

    <div class="form-group">
      <label for="password" class="control-label col-sm-4">Password</label>

      <div class="col-sm-8">
        <input type="password" class="form-control" name="password" id="password">
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-8 col-sm-offset-4">
        <button type="submit" class="btn" role="button">Login</button>
      </div>
    </div>
  </form>

  <p class="text-center">
    Copyright &copy;
    <span id="year" class="mr5"></span>
    <span>Federal School of Dental Technology &amp; Therapy Enugu</span>
  </p>

  <?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

  <script>
    document.getElementById("year").innerHTML = new Date().getFullYear();
  </script>
</body>
</html>
