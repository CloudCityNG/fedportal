<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php') ?>
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/../../libs/css/main.min.css', true) ?>">
  <link rel="stylesheet"
        href="<?php echo path_to_link(__DIR__ . '/css/edu-history.min.css', true) ?>"/>
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
                <form class="well" method="post"
                      data-fv-framework="bootstrap"
                      data-fv-message="This value is not valid"
                      data-fv-icon-valid="glyphicon glyphicon-ok"
                      data-fv-icon-invalid="glyphicon glyphicon-remove"
                      data-fv-icon-validating="glyphicon glyphicon-refresh">
                  <input type="hidden" name="reg_no" value="<?php echo $_SESSION['REG_NO']; ?>"/>

                  <?php include(__DIR__ . '/primary-edu.html'); ?>

                  <hr/>

                  <?php include(__DIR__ . '/secondary-edu.html'); ?>

                  <hr/>

                  <?php include(__DIR__ . '/post-secondary.html'); ?>

                  <hr/>

                  <?php include(__DIR__ . '/o-levels.html'); ?>

                  <div class="text-center">
                    <input class="btn btn-primary btn-lg" type="submit" value="Submit"/>
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

  <?php include_once(__DIR__ . '/../../includes/js-footer.php') ?>

  <script
    src="<?php echo path_to_link(__DIR__ . '/js/edu-history.min.js', true) ?>"></script>
</body>
</html>
