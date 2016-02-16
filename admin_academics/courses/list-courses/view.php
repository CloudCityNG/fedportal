<table class="table table-striped table-condense table-bordered course-list-table">
  <thead>
    <tr>
      <th>S/N</th>
      <th>Title</th>
      <th>Code</th>
      <th>Units</th>
      <th>Department</th>
      <th>Semester</th>
      <th>Class</th>
      <th>Active</th>
      <th></th>
    </tr>
  </thead>

  <tbody>
    <?php
    $count = 1;
    $departments = $listCoursesContext['departments'];

    foreach ($listCoursesContext['course_list'] as $course) {
      $courseLink = '';
      if (UserSession::isCapable('can_edit_course')) {
        $courseLink = path_to_link(__DIR__ . '/..') . "?create-course&course_id={$course['id']}";
      }

      $activeIconClass = $course['active'] ? 'ok' : 'remove';

      echo "
      <tr>
        <td style='text-align: right'>{$count}</td>
        <td>{$course['title']}</td>
        <td>{$course['code']}</td>
        <td style='text-align: center'>{$course['unit']}</td>
        <td>{$departments[$course['department']]}</td>
        <td style='text-align: center'>{$course['semester']}</td>
        <td style='width: 60px;'>{$course['class']}</td>
        <td class='course-is-active'>
          <span class='glyphicon glyphicon-{$activeIconClass}'></span>
        </td>
        <td>
          <a href='{$courseLink}' class='glyphicon glyphicon-pencil'></a>
        </td>
      </tr>
      ";
      $count++;
    }
    ?>
  </tbody>
</table>
