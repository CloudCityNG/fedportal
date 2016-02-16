<?php
require_once(__DIR__ . '/../../helpers/get_photos.php');
$photo = get_photo(null, true);
$photoPath = $photo ? $photo : STATIC_ROOT . 'includes/img/faceless.jpg';
$regNo = $_SESSION['REG_NO'];

$form_completion1 = false;
$form_completion1_class = 'alert-danger';
$form_completion1_message = '';

if (isset($_SESSION['STUDENT-REG-FORM-REGISTRATION'])) {
  $form_completion = json_decode($_SESSION['STUDENT-REG-FORM-REGISTRATION']);
  $form_completion1 = true;

  if (isset($form_completion->error)) {
    $form_completion1_message = "<strong>Error!</strong> " . $form_completion->error->message;

  } elseif (isset($form_completion->success)) {

    $form_completion1_message = "<strong>Congrats!</strong> " . $form_completion->success->message;
    $form_completion1_class = 'alert-success';
  }
}

unset($_SESSION['STUDENT-REG-FORM-REGISTRATION']);
?>

<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/css/student-portal-home.min.css', true) ?>"/>

  <?php if (isset($pageCssPath)) echo "<link rel='stylesheet' href='{$pageCssPath}'/>"; ?>
</head>

<body>
  <div class="container-student-portal">
    <div class="top-info">
      <div class="legend clearfix">
        <div class="pull-left">
          <a href="<?php echo path_to_link(__DIR__ . '/') ?>">Student Portal</a>
        </div>

        <div class="pull-right">
          <a href="<?php echo path_to_link(__DIR__ . '/../login/logout.php'); ?>">
            <img src="<?php echo path_to_link(__DIR__ . '/../../img/exit.png') ?>" alt="log off"/>
          </a>
        </div>
      </div>

      <div class="divider clearfix">
        <span class="date-time pull-left"><?php echo date('l, F j, Y', time()); ?></span>

        <div class="pull-right">
          <div class="pull-left date-time" style="margin-right: 5px;">
            <?php
            $user = UserSession::user();
            if ($user) echo "Logged in as: {$user['username']} ({$user['full_name']})";
            ?>
          </div>

          <div class="pull-right">
            <img width="35px" src="<?php echo $photoPath ?>" alt="user" title="user"
                 id="student-photo" style="border-radius: 50%;">
          </div>
        </div>
      </div>
    </div>

    <?php require(__DIR__ . '/../../admin_academics/home/current_session_semester_info.php'); ?>

    <div class="row content-area">

      <div class="col-sm-3 side-bar-navs navigation-controls">
        <div class="side-nav bio-data-side-bar-nav">
          <span class="title">New student registration</span>

          <div class='links'>
            <a class='link' href="<?php echo path_to_link(__DIR__ . '/../bio_data1') ?>">Bio Data</a>
            <a class='link' href="<?php echo path_to_link(__DIR__ . '/../edu_history1') ?>">Education History</a>
            <a class='link' href="<?php echo path_to_link(__DIR__ . '/../medicals1') ?>">Medicals</a>
          </div>
        </div>

        <div class="side-nav course-reg-side-bar-nav">
          <span class="title">Course registration</span>

          <div class='links'>
            <a class='link' href="<?php echo path_to_link(__DIR__ . '/../course_reg1') ?>">Sign up for courses</a>
          </div>
        </div>
      </div>

      <div class="col-sm-9 content-area-main">
        <div class="h4 alert <?php echo $form_completion1_class; ?> alert-dismissible form-completion-success-alert"
             role="alert" style="display: <?php echo $form_completion1 ? 'block' : 'none' ?>;">

          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

          <?php echo $form_completion1_message; ?>
        </div>

        <div class="content-area-main-insert-template">
          <?php if (isset($link_template) && $link_template) require($link_template); ?>
        </div>
      </div>
    </div>
  </div>

  <?php include(__DIR__ . '/../../includes/js-footer.php'); ?>
  <script src="<?php echo path_to_link(__DIR__ . '/js/home.min.js', true) ?>"></script>
  <?php if (isset($pageJsPath) && $pageJsPath) {
    echo "<script src='{$pageJsPath}'></script>";
  } ?>

</body>
</html>
