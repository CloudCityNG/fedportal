<?php
require_once(__DIR__ . '/current_session_semester_info.php');
require_once(__DIR__ . '/getNavClass.php');
?>

<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo path_to_link(__DIR__ . '/css/home.min.css') ?>"/>

  <?php if (isset($pageCssPath)) {
    echo "<link rel='stylesheet' href='{$pageCssPath}'/>";
  } ?>
</head>

<body>
<div class="container-admin-acada">
  <div class="top-info">
    <div class="legend">
      <a href="<?php echo path_to_link(__DIR__ . '/') ?>">Academics Administration</a>
    </div>

    <div class="divider">
      <span class="date-time"><?php echo date('l, F j, Y', time()); ?></span>
    </div>
  </div>

  <div class="session-semester-info row">
    <div class="col-sm-6">
      <div class="panel <?php echo $currentSemesterInfo['panel-class'] ?>">
        <div class="panel-heading">
          <h3 class="panel-title"> <?php echo $currentSemesterInfo['semester'] ?> Semester</h3>
        </div>

        <div class="panel-body">
          <div><strong>Started:&nbsp;&nbsp;&nbsp;</strong> <?php echo $currentSemesterInfo['start'] ?> </div>
          <div><strong>Ends:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> <?php echo $currentSemesterInfo['end'] ?>
          </div>
        </div>

        <div class="panel-footer">
          Ends in: <span class="h3"><?php echo $currentSemesterInfo['diff'] ?> Days</span>
        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h2 class="panel-title"> <?php echo $currentSessionInfo['session']?> Session</h2>
        </div>

        <div class="panel-body">
          <div><strong>Started:&nbsp;&nbsp;&nbsp;</strong>
            <?php echo $currentSessionInfo['start'] ?>
          </div>

          <div>
            <strong>Ends:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong> <?php echo $currentSessionInfo['end'] ?>
          </div>
        </div>

        <div class="panel-footer">
          Ends in: <span class="h3"><?php echo $currentSessionInfo['diff'] ?> Days</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row content-area">
    <div class="col-sm-3 side-bar-navs">
      <div class="side-nav session-side-bar-nav <?php echo getNavClass($currentPage, 'nav', 'session') ?>">

        <span class="title session-title">Manage Academic Sessions</span>

        <div class="links <?php echo getNavClass($currentPage, 'links', 'session') ?>">
          <a class="link <?php echo getNavClass($currentPage, 'link', 'new-session') ?>"
             href="<?php echo STATIC_ROOT . 'admin_academics/academic_session/' ?>">
            Current and New Academic Session
          </a>
        </div>
      </div>

      <div class="side-nav semester-side-bar-nav <?php echo getNavClass($currentPage, 'nav', 'semester'); ?>">

        <span class="title">Manage Semester</span>

        <div class="links <?php echo getNavClass($currentPage, 'links', 'semester') ?>">
          <a class="link <?php echo getNavClass($currentPage, 'link', 'new-semester') ?>"
             href="<?php echo path_to_link(__DIR__ . '/../semester/') ?>">
            Current and New Semester
          </a>
        </div>
      </div>

      <div class="side-nav <?php echo getNavClass($currentPage, 'nav', 'assessment'); ?>">
        <span class="title">Exams And Assessments</span>

        <div class="links <?php echo getNavClass($currentPage, 'links', 'assessment') ?>">
          <a class="link <?php echo getNavClass($currentPage, 'link', 'enter-grades') ?>"
             href="<?php echo path_to_link(__DIR__ . '/../assessment/') ?>">
            Grade Students
          </a>
        </div>
      </div>

      <div class="side-nav collapsed">
        <span class="title">Courses</span>

        <div class="links">
          <span class="link">View Course</span>

          <div class="link" id="add-course"
               data-template-url="<?php echo STATIC_ROOT . 'admin_academics/courses/' ?>">
            Add Course
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-9 content-area-main">
      <div class="alert-container"></div>

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

<script src="<?php echo path_to_link(__DIR__ . '/js/home.min.js') ?>"></script>

<?php
if (isset($pageJsPath) && $pageJsPath) {
  echo "<script src='{$pageJsPath}'></script>";
}
?>

</body>
</html>
