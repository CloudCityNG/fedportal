<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
</head>

<link rel="stylesheet" href="<?php echo STATIC_ROOT . 'course_reg/css/course-reg.css' ?>"/>

<body>
<div class="app">
  <?php include(__DIR__ . '/../../includes/student-reg/nav.php');; ?>

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

              <?php include(__DIR__ . '/course_reg_view_print.php'); ?>

              <p class="back-to-main" style="display: none;">Back to main</p>

              <?php include(__DIR__ . '/course_reg_form.php') ?>
            </div>
          </section>
        </div>
      </div>
    </section>
  </section>
</div>

<?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . '/student_portal/course_reg/js/register-courses.js'?>"></script>
</body>
</html>
