<?php

require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/StudentRegistration.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

$student = new StudentRegistration;
?>

<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php') ?>
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/../../libs/css/main.min.css', true) ?>">
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/css/student-portal-home.min.css', true) ?>"/>
</head>

<body>
  <div class="app horizontal-layout">
    <?php include(__DIR__ . '/../includes/nav.php') ?>

    <section class="layout">

      <!-- main content -->
      <section class="main-content">

        <!-- content wrapper -->
        <div class="content-wrap">

          <!-- inner content wrapper -->
          <div class="wrapper">
            <div
              class="h4 alert <?php echo $student->form_completion_class; ?> alert-dismissible form-completion-success-alert"
              role="alert" style="display: <?php echo $student->form_completion ? 'block' : 'none' ?>;"
              >

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>

              <?php echo $student->form_completion_message; ?>
            </div>

            <div class="jumbotron registration-statuses">
              <div class="legend h3">
                Completion Status
              </div>

              <div class="h3 alert <?php echo $student->html_status_classes['bio_data']; ?> ">
                <a href="">
                  Bio Data [<?php echo $student->html_status_texts['bio_data'] ?>]
                </a>
              </div>

              <div class="h3 alert <?php echo $student->html_status_classes['edu_history']; ?> ">
                <a href="">
                  Education History [<?php echo $student->html_status_texts['edu_history'] ?>]
                </a>
              </div>

              <div class="h3 alert <?php echo $student->html_status_classes['photo']; ?> ">
                <a href="">
                  Photo Upload [<?php echo $student->html_status_texts['photo'] ?>]
                </a>
              </div>

              <div class="h3 alert <?php echo $student->html_status_classes['medicals']; ?> ">
                <a href="">
                  Medical Records [<?php echo $student->html_status_texts['medicals'] ?>]
                </a>
              </div>

              <div class="h3 alert <?php echo $student->html_status_classes['courses']; ?> ">
                <a href="">
                  Course Registration [<?php echo $student->html_status_texts['courses'] ?>]
                </a>
              </div>
            </div>
          </div>
          <!-- /inner content wrapper -->

        </div>
        <!-- /content wrapper -->
        <a class="exit-offscreen"></a>
      </section>
      <!-- /main content -->

    </section>

  </div>

  <?php include(__DIR__ . '/../../includes/js-footer.php') ?>
</body>
</html>
