<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>

  <link rel="stylesheet"
        href="<?php echo STATIC_ROOT . 'admin_academics/home/css/home.min.css' ?>"/>

  <link rel="stylesheet"
        href="<?php echo STATIC_ROOT . 'admin_academics/semester/css/semester.css' ?>"/>

  <link rel="stylesheet"
        href="<?php echo STATIC_ROOT . 'admin_academics/academic_session/css/session.css' ?>"/>
</head>

<body>
<div class="app horizontal-layout">
  <?php include(__DIR__ . '/../includes/nav.php'); ?>

  <section class="layout">
    <section class="main-content">
      <div class="content-wrap">
        <div class="wrapper">
          <div class="top-info">
            <div class="legend">Academics Administration</div>

            <div class="divider">
              <span class="date-time"><?php echo $today; ?></span>
            </div>
          </div>

          <div class="row content-area">
            <div class="col-sm-3 side-bar-navs">
              <div class="side-nav session-side-bar-nav collapsed">
                <span class="title">Session</span>

                <div class="links">
                  <span class="link" id="session"
                        data-template-url="<?php echo STATIC_ROOT . 'admin_academics/academic_session/' ?>">
                    Create New Academic Session
                  </span>
                </div>
              </div>

              <div class="side-nav semester-side-bar-nav collapsed">
                <span class="title">Semester</span>

                <div class="links">
                  <span class="link" id="semester"
                        data-template-url="<?php echo STATIC_ROOT . 'admin_academics/semester/' ?>">
                    Change Semester
                  </span>
                </div>
              </div>

              <div class="side-nav collapsed">
                <span class="title">Courses</span>

                <div class="links">
                  <span class="link">View Course</span>

                  <div class="link"  id="add-course"
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
                <!--              insert templates from links here-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
</div>

<?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . 'admin_academics/home/js/home-bundle.min.js' ?>"></script>
</body>
</html>