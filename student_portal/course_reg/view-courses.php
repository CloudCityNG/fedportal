<div class="student-course-form-view">
  <div style="text-align: center; font-size: 18px">
    <div style="font-weight: bold;">
      You have registered courses for <?php
      echo "{$registerCoursesSemesterText} semester {$registerCoursesAcademicYear} session";
      ?>
    </div>

    <p>
      Click <a href="<?php echo $printCourseFormLink; ?>">here</a> to print course form
    </p>
  </div>

  <table class="table table-striped table-condensed table-bordered student-course-form-view-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Course Code</th>
        <th>Course Title</th>
        <th>Credit Unit</th>
      </tr>
    </thead>

    <tbody>
      <?php
      $courseSeq = 1;

      foreach ($course_data as $courses) {
        $unit = sprintf('%.2f', $courses['unit']);

        echo "<tr>
                <td align='right'>{$courseSeq}</td>
                <td align='center'>{$courses['code']}</td>
                <td>{$courses['title']}</td>
                <td align='right'>{$unit}</td>
              </tr>";

        $courseSeq++;
      }
      ?>
    </tbody>
  </table>
</div>
