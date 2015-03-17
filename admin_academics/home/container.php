<?php
function getNavClass($currentPage, $section, $value)
{
  if ($currentPage) {
    switch ($section) {
      case 'nav':
        return $currentPage['title'] == $value ? 'expanded' : 'collapsed';

      case 'links':
        return $currentPage['title'] == $value ? 'active' : '';

      case 'link':
        return $currentPage['link'] == $value ? 'selected current' : '';
    }
  }

  return $section == 'nav' ? 'collapsed' : '';
}

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

<!--  --><?php //include(__DIR__ . '/../includes/nav.php'); ?>
<div class="container-admin-acada">
  <div class="top-info">
    <div class="legend">
      <a href="<?php echo path_to_link(__DIR__ . '/') ?>">Academics Administration</a>
    </div>

    <div class="divider">
      <span class="date-time"><?php echo date('l, F j, Y', time()); ?></span>
    </div>
  </div>

  <div class="row content-area">
    <div class="col-sm-3 side-bar-navs">
      <div class="side-nav session-side-bar-nav <?php echo getNavClass($currentPage, 'nav', 'session') ?>">

        <span class="title session-title">Manage Academic Sessions</span>

        <div class="links <?php echo getNavClass($currentPage, 'links', 'session') ?>">
                  <span
                    class="link <?php echo getNavClass($currentPage, 'link', 'new-session') ?>">
                    <a href="<?php echo STATIC_ROOT . 'admin_academics/academic_session/' ?>">
                      Current and New Academic Session
                    </a>
                  </span>
        </div>
      </div>

      <div
        class="side-nav semester-side-bar-nav <?php echo getNavClass($currentPage, 'nav', 'semester'); ?>">

        <span class="title">Manage Semester</span>

        <div class="links <?php echo getNavClass($currentPage, 'links', 'semester') ?>">
                  <span class="link <?php echo getNavClass($currentPage, 'link', 'new-semester') ?>">
                    <a href="<?php echo path_to_link(__DIR__ . '/../semester/') ?>">Current and New Semester</a>
                  </span>
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

      <div class="side-nav collapsed">
        <span class="title">xxx</span>
      </div>

      <div class="side-nav collapsed">
        <span class="title">xxx</span>
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
