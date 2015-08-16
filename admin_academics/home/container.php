<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/css/admin-academics-home.min.css', true) ?>"/>

  <?php if (isset($pageCssPath)) {
    echo "<link rel='stylesheet' href='{$pageCssPath}'/>";
  } ?>
</head>

<body>
  <div class="container-admin-acada">
    <div class="top-info">
      <div class="legend clearfix">
        <div class="pull-left">
          <a href="<?php echo path_to_link(__DIR__ . '/') ?>">Academics Administration</a>
        </div>

        <div class="pull-right">
          <a href="<?php echo path_to_link(__DIR__ . '/../login/logout.php'); ?>">
            Log Off <img src="<?php echo path_to_link(__DIR__ . '/../../img/exit.png') ?>" alt="log off"/>
          </a>
        </div>
      </div>

      <div class="divider">
        <span class="date-time"><?php echo date('l, F j, Y', time()); ?></span>
      </div>
    </div>

    <?php require(__DIR__ . '/current_session_semester_info.php'); ?>

    <div class="row content-area">

      <div class="col-sm-3 side-bar-navs">
        <div class="side-nav session-side-bar-nav">

          <span class="title session-title">Manage Academic Sessions</span>

          <div class="links">
            <a class="link" href="<?php echo STATIC_ROOT . 'admin_academics/academic_session/' ?>">
              Current and New Academic Session
            </a>
          </div>
        </div>

        <div class="side-nav semester-side-bar-nav">
          <span class="title">Manage Semester</span>

          <div class="links">
            <a class="link" href="<?php echo path_to_link(__DIR__ . '/../semester/') ?>">Current and New Semester</a>
          </div>
        </div>

        <div class="side-nav side-nav-exams-assessment">
          <span class="title">Exams And Assessments</span>

          <div class="links">
            <a class="link" href="<?php echo path_to_link(__DIR__ . '/../assessment/') . '?grade-students' ?>">
              Grade Students
            </a>

            <a class="link" href="<?php echo path_to_link(__DIR__ . '/../assessment/') . '?transcripts' ?>">
              Transcripts
            </a>

            <a class="link" href="<?php echo path_to_link(__DIR__ . '/../assessment/') . '?publish-results' ?>">
              Publish Results
            </a>
          </div>
        </div>

        <div class="side-nav side-nav-courses">
          <span class="title">Courses</span>

          <div class="links">

          </div>
        </div>
      </div>

      <div class="col-sm-9 content-area-main">
        <div class="content-area-main-insert-template">
          <?php
          if (isset($link_template) && $link_template) {
            require($link_template);
          }
          ?>
        </div>
      </div>
    </div>
  </div>


  <?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

  <script src="<?php echo path_to_link(__DIR__ . '/js/admin-academics-home.min.js', true) ?>"></script>

  <?php
  if (isset($pageJsPath) && $pageJsPath) {
    echo "<script src='{$pageJsPath}'></script>";
  }
  ?>

</body>
</html>
