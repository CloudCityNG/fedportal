<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo STATIC_ROOT . 'libs/css/main.min.css' ?>">
  <link rel="stylesheet" href="<?php echo STATIC_ROOT . 'student_portal/course_reg/css/course-reg.min.css' ?>"/>
</head>

<body>
<div class="app">
  <?php include(__DIR__ . '/../includes/nav.php') ?>

  <section class="layout">
    <section class="main-content">
      <div class="content-wrap">
        <div class="wrapper">
          <section class="panel">
            <div class="panel-body">
              <?php require($view); ?>
            </div>
          </section>
        </div>
      </div>
    </section>
  </section>
</div>

<?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . 'student_portal/course_reg/js/register-courses.js' ?>"></script>
</body>
</html>
