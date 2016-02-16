<div class="side-nav side-nav-courses">
  <span class="title">Courses</span>

  <div class="links">
    <?php
    if (UserSession::isCapable('can_list_courses')){
      $listCoursesLink = path_to_link(__DIR__ . '/../courses/') . '?list-courses';
      echo "<a class='link' href='{$listCoursesLink}'>List of courses</a>";
    }

    if (UserSession::isCapable('can_create_course')){
      $createCourseLink = path_to_link(__DIR__ . '/../courses/') . '?create-course';
      echo "<a class='link' href='{$createCourseLink}'>Create course</a>";
    }
    ?>
  </div>
</div>
