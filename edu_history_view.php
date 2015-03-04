<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/includes/header.php') ?>
</head>

<link rel="stylesheet"
      href="<?php echo STATIC_ROOT . 'includes/student-reg/edu-history/styles.css' ?>"/>

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
              <form class="well" novalidate method="post">
                <input type="hidden" name="reg_no" value="<?php echo $_SESSION['REG_NO']; ?>"/>

                <?php include(__DIR__ . '/includes/student-reg/edu-history/primary-edu.html'); ?>

                <hr/>

                <?php include(__DIR__ . '/includes/student-reg/edu-history/secondary-edu.html'); ?>

                <hr/>

                <?php include(__DIR__ . '/includes/student-reg/edu-history/post-secondary.html'); ?>

                <hr/>

                <?php include(__DIR__ . '/includes/student-reg/edu-history/o-levels.html'); ?>

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

<?php include_once(__DIR__ . '/includes/js-footer.php') ?>

<script
  src="<?php echo STATIC_ROOT . 'includes/student-reg/edu-history/js/scripts.js' ?>"></script>
</body>
</html>
