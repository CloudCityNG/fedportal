<?php require_once(__DIR__ . '/../../helpers/app_settings.php') ?>
<header class="header header-fixed navbar">

  <div class="brand">
    <a href="javascript:;" class="ti-menu navbar-toggle off-left visible-xs" data-toggle="collapse"
       data-target="#hor-menu"></a>

    <a href="index.php" class="navbar-brand">
      <img src="<?php echo STATIC_ROOT . 'img/logo.png' ?>" alt="logo.png">
      <span class="heading-font">easyVarsity</span>
    </a>
  </div>


  <div class="collapse navbar-collapse pull-left" id="hor-menu">
    <ul class="nav navbar-nav">
      <li class="dropdown">
        <a href="javascript:;" data-toggle="dropdown">
          <span>Set Up</span>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a href="newsession.php">
              <span>New Session</span>
            </a>
          </li>
          <li>
            <a href="courses.php">
              <span>Courses and Units</span>
            </a>
          </li>

        </ul>
      </li>
      <li class="dropdown">
        <a href="javascript:;" data-toggle="dropdown">
          <span>Edit</span>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">

          <li>
            <a href="edit_courses.php">
              <span>Edit Courses</span>
            </a>
          </li>
          <li>
            <a href="edit_student.php">
              <span>Edit Student Profile</span>
            </a>
          </li>
          <li>
            <a href="edit_staff.php">
              <span>Edit Staff Profile</span>
            </a>
          </li>
          <li>
            <a href="edit_session.php">
              <span>Edit Session</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="dropdown">
        <a href="javascript:;" data-toggle="dropdown">
          <span>Actions</span>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a href="grade_students.php">
              <span>Grade Students</span>
            </a>
          </li>
          <li>
            <a href="matricno.php">
              <span>Assign Matric No</span>
            </a>
          </li>
          <li>
            <a href="delete_student.php">
              <span>Delete Student</span>
            </a>
          </li>
          <li>
            <a href="graduate_student.php">
              <span>Graduate Student</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="dropdown">
        <a href="javascript:;" data-toggle="dropdown">
          <span>Reports</span>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a href="academic_reports.php">
              <span>View Academic Reports</span>
            </a>
          </li>
          <li>
            <a href="enrolment.php">
              <span>Enrolments per Session</span>
            </a>
          </li>
          <li>
            <a href="view_students.php">
              <span>View Students' Demographics</span>
            </a>
          </li>
          <li>
            <a href="view_staff.php">
              <span>View Staff</span>
            </a>
          </li>

        </ul>
      </li>

      <li>
        <a href="staff.php">
          Register Staff Profiles
        </a>
      </li>

      <li>
        <a href="backup.php">
          Backup Portal Offline </a>
      </li>

    </ul>
  </div>

  <ul class="nav navbar-nav navbar-right">


    <li class="off-right">
      <a href="<?php echo STATIC_ROOT . 'admin_academics/login/logout.php'?>">Sign out</a>
    </li>


  </ul>
  </li>
  </ul>
</header>
