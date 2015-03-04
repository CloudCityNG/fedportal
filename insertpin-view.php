<?php
include_once(__DIR__ . '/helpers/app_settings.php');
?>

<!doctype html>
<html lang="en">
<head>
  <?php include(__DIR__ . '/includes/header.php') ?>
</head>

<style>
  body {
    background-color: initial;
  }

  .insert-pin-content {
    max-width: 800px;
    margin: 150px auto;
    border: 1px solid #D7D7E0;
    padding: 5px;
    box-sizing: content-box;
    border-radius: 5px;
    min-height: 300px;
  }

  form {
    max-width: 75%;
    margin: 50px auto 50px auto;
  }

  #create-pin-form{
    margin-bottom: 30px;
  }

  .alert{
    max-width: 75%;
    margin: auto auto 50px;
  }
</style>

<body>
<div class="insert-pin-content">
  <form class="form-inline" role="form" data-toggle="validator" id="log-in-form" method="post">
    <div class="form-group">
      <label class="sr-only" for="username">Username</label>

      <input class="form-control" name="username" id="username" placeholder="Username" required/>
    </div>

    <div class="form-group">
      <label class="sr-only" for="password">Password</label>

      <input class="form-control" name="password" id="password" placeholder="Password" required/>
    </div>

    <button class="btn btn-default" type="submit" id="log-in-form-btn">Let me in</button>
  </form>

  <div class="create-pin-form-container"></div>

  <div class="alert alert-dismissible alert-success" role="alert" style="display: none">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>

    <span class="message"></span>
  </div>
</div>

<?php include_once(__DIR__ . '/includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . '/insertpin.js' ?>"></script>

<script>
  var url = "<?php echo STATIC_ROOT . 'insertpin1.php'?>";
</script>
</body>
</html>