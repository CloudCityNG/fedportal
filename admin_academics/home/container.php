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
          <a href="<?php echo path_to_link(__DIR__ . '/') ?>">Administration</a>
        </div>

        <div class="pull-right">
          <a href="<?php echo path_to_link(__DIR__ . '/../login/logout.php'); ?>">
            Log Off <img src="<?php echo path_to_link(__DIR__ . '/../../img/exit.png') ?>" alt="log off"/>
          </a>
        </div>
      </div>

      <div class="divider clearfix">
        <span class="date-time pull-left"><?php echo date('l, F j, Y', time()); ?></span>

        <div class="date-time pull-right">
          <?php
          $user = UserSession::user();
          if ($user) echo "Logged in as: {$user['username']} ({$user['full_name']})";
          ?>
        </div>
      </div>
    </div>

    <?php require(__DIR__ . '/current_session_semester_info.php'); ?>

    <div class="row content-area">

      <div class="col-sm-3">
        <div class="km-side-nav side-bar-navs navigation-controls ">
          <?php if (UserSession::isCapable('can_view_session')) require(__DIR__ . '/session-link.php') ?>
          <?php if (UserSession::isCapable('can_view_semester')) require(__DIR__ . '/semester-link.php') ?>
          <?php if (UserSession::isCapable('can_view_exams')) require(__DIR__ . '/assessment-link.php') ?>
          <?php if (UserSession::isCapable('can_view_courses')) require(__DIR__ . '/courses-link.php') ?>
          <?php if (UserSession::isCapable('can_view_staff_profile')) require(__DIR__ . '/staff-profile-link.php') ?>
        </div>
      </div>

      <div class="col-sm-9 content-area-main">
        <div class="content-area-main-insert-template">
          <?php if (isset($link_template) && $link_template) require($link_template); ?>
        </div>
      </div>
    </div>
  </div>

  <?php include(__DIR__ . '/../../includes/js-footer.php'); ?>
  <script src="<?php echo path_to_link(__DIR__ . '/js/admin-academics-home.min.js', true) ?>"></script>
  <?php if (isset($pageJsPath) && $pageJsPath) echo "<script src='{$pageJsPath}'></script>"; ?>

</body>
</html>
