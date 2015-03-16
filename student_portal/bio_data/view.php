<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php') ?>

  <link rel="stylesheet"
        href="<?php echo STATIC_ROOT . 'student_portal/home/css/centralize.min.css' ?>"/>
</head>

<body>
<div class="app">
  <?php include(__DIR__ . '/../includes/nav.php') ?>

  <section class="layout">
    <!-- main content -->
    <section class="main-content">
      <!-- content wrapper -->
      <div class="content-wrap">

        <!-- inner content wrapper -->
        <div class="wrapper">
          <section class="panel">
            <div class="panel-body">
              <form role="form" method="post" class="well" id="bio-data-form"
                    enctype="multipart/form-data"
                    data-fv-framework="bootstrap"
                    data-fv-message="This value is not valid"
                    data-fv-icon-valid="glyphicon glyphicon-ok"
                    data-fv-icon-invalid="glyphicon glyphicon-remove"
                    data-fv-icon-validating="glyphicon glyphicon-refresh">

                <input name="student_bio[personalno]"
                       type="hidden"
                       value="<?php echo $reg_no; ?>">

                <fieldset>
                  <legend class="text-center">Bio Data</legend>

                  <?php include(__DIR__ . '/bio-data-form.php') ?>
                </fieldset>

                <fieldset>
                  <legend class="text-center">Upload Your Photo</legend>

                  <div class="form-group">
                    <input class="form-control" type="file" id="photo" name="photo" required
                           data-fv-file="true"
                           data-fv-file-type="image/jpeg,image/png,image/gif,image/png,image/tiff"
                           data-fv-file-maxsize="51200"/>
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

<?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . 'student_portal/bio_data/js/bio-data.js'?>"></script>
</body>
</html>
