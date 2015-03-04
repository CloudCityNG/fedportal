<header class="header header-fixed navbar">

  <div class="brand">
    <!-- toggle offscreen menu -->
    <a href="javascript:;" class="ti-menu navbar-toggle off-left visible-xs"
       data-toggle="collapse" data-target="#hor-menu"></a>
    <!-- /toggle offscreen menu -->

    <a href="student_dashboard.php" class="navbar-brand">
      <img src="img/logo.png" alt="">
      <span class="heading-font">easyVarsity</span>
    </a>
  </div>


  <div class="collapse navbar-collapse pull-left" id="hor-menu">
    <ul class="nav navbar-nav">
      <li class="dropdown">
        <a href="javascript:;" data-toggle="dropdown">
          <span>Fill forms</span>
          <b class="caret"></b>
        </a>

        <ul class="dropdown-menu">
          <li>
            <a href="freshman_reg.php">
              <span>Bio Data</span>
            </a>
          </li>

          <li>
            <a href="edu_history.php">
              <span>Education History</span>
            </a>
          </li>

          <li>
            <a href="medicals_reg.php">
              <span>Medical Records</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="dropdown">
        <a href="javascript:;" data-toggle="dropdown">
          <span>Academics</span>
          <b class="caret"></b>
        </a>

        <ul class="dropdown-menu">
          <li>
            <a href="course_reg.php">
              <span>Course Registration</span>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="course_reg.php">
          View &amp; Print Information
        </a>
      </li>
    </ul>
  </div>

  <ul class="nav navbar-nav navbar-right">
    <li class="notifications dropdown">
      <a href="javascript:;" data-toggle="dropdown">
        <i class="ti-bell"></i>

        <div class="badge badge-top bg-danger animated flash">
          <span>0</span>
        </div>
      </a>

      <div class="dropdown-menu animated fadeInLeft">
        <div class="panel panel-default no-m">
          <div class="panel-heading small"><b>Notifications</b>
          </div>
        </div>
      </div>
    </li>

    <li class="off-right">
      <a href="javascript:;" data-toggle="dropdown">
        <?php
        include_once(__DIR__ . '/../../helpers/get_photos.php');

        include_once(__DIR__ . '/../../helpers/app_settings.php');

        $photo = get_photo(null, true);

        $photo_path = $photo ? $photo : STATIC_ROOT . 'img/faceless.jpg';
        ?>
        <img src="<?php echo $photo_path ?>" class="header-avatar img-circle"
             alt="user" title="user" id="student-photo">
        <span class="hidden-xs ml10">More Options</span>
        <i class="ti-angle-down ti-caret hidden-xs"></i>
      </a>
      <ul class="dropdown-menu animated fadeInRight">
        <li>
          <a href="logout.php">Logout</a>
        </li>

        <li>
          <a href="#">Report an Issue</a>
        </li>
      </ul>
    </li>
  </ul>
</header>
