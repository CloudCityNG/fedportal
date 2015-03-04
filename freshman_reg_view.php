<?php include_once(__DIR__ . '/helpers/app_settings.php'); ?>

<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/includes/header.php') ?>
</head>

<link rel="stylesheet" href="<?php echo STATIC_ROOT . 'medicals_reg/css/styles.css' ?>"/>

<body>
<div class="app">
  <?php include(__DIR__ . '/includes/student-reg/nav.php') ?>

  <section class="layout">
    <!-- main content -->
    <section class="main-content">
      <!-- content wrapper -->
      <div class="content-wrap">

        <!-- inner content wrapper -->
        <div class="wrapper">
          <section class="panel">
            <div class="panel-body">
              <form role="form" method="post" class="well" data-toggle="validator" enctype="multipart/form-data">
                <fieldset>
                  <legend class="text-center">Bio Data</legend>

                  <?php include(__DIR__ . '/includes/student-reg/bio-data-form.php') ?>
                </fieldset>

                <fieldset>
                  <legend class="text-center">Upload Your Photo</legend>

                  <div class="form-group">
                    <input class="form-control" type="file" id="photo" name="photo" required/>
                    <span>*** Please ensure that your photo does not exceed 50kb in size.</span>
                  </div>
                </fieldset>

                <div class="text-center">
                  <button class="btn btn-primary btn-lg" type="submit">Register</button>
                </div>
              </form>
            </div>
          </section>
        </div>
        <!-- /inner content wrapper -->

      </div>
      <!-- /content wrapper -->
      <a class="exit-offscreen"></a>
    </section>
    <!-- /main content -->
  </section>

</div>

<?php include(__DIR__ . '/includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . 'freshman_reg.js'?>"></script>

</body>
</html>
