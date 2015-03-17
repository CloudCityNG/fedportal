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

              <?php
              if ($already_registered) {
                echo "<h4 class='already-registered'>
                     <p>You are signed up for courses for $semester_text semester
                     Please print course form if you have not done so.</p>

                     <span class='printer-friendly'>Click here for printer friendly view.</span>
                     </h4>";
              }
              ?>

              <?php include(__DIR__ . '/view_print.php'); ?>

              <p class="back-to-main" style="display: none;">Back to main</p>

              <?php include(__DIR__ . '/form.php') ?>
            </div>
          </section>
        </div>
      </div>
    </section>
  </section>
</div>

<?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . 'student_portal/course_reg/js/register-courses.js'?>"></script>
</body>
</html>
