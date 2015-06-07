<?php
$sessionSemesterInfo = "{$semester_text} semester {$academicYear} session";
?>

<div class="view-registered-courses">

  <h4 class="view-registered-courses-info">
    You are signed up for courses for <?php echo $sessionSemesterInfo ?> Please
    <a href="<?php echo STATIC_ROOT . 'student_portal/view_info/' ?>">print course form</a>
    if you have not done so.
  </h4>

  <div class="view-registered-courses-course-data">
    <h4 class="text-center">
      <strong>COURSE REGISTRATION</strong>
    </h4>

    <table class="table table-bordered table-responsive table-striped img-and-name">
      <tbody>
        <tr>
          <th>NAME OF STUDENT</th>
          <td><?php echo $student['names']; ?></td>
        </tr>

        <tr>
          <th>REGISTRATION NO</th>
          <td><?php echo $regNo; ?></td>
        </tr>

        <tr>
          <th>DEPARTMENT</th>
          <td><?php echo $dept_name; ?></td>
        </tr>

        <tr>
          <th>LEVEL</th>
          <td><?php echo $currentLevel; ?></td>
        </tr>

        <tr>
          <th>SESSION</th>
          <td><?php echo $sessionSemesterInfo ?></td>
        </tr>
      </tbody>
    </table>

    <table class="table table-striped table-condensed table-bordered view-print-course-table">

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
        $course_seq = 1;
        $sumUnits = 0;

        foreach ($course_data as $courses) {
          $code = $courses['code'];
          $title = $courses['title'];
          $unit = floatval($courses['unit']);
          $sumUnits += $unit;
          $unit = sprintf('%.2f', $unit);

          echo "<tr>\n" .

            "<td>$course_seq</td>\n" .

            "<td>$code</td>\n" .

            "<td>$title</td>\n" .

            "<td class='text-center'>$unit</td>\n" .

            "</tr>";

          $course_seq++;
        }

        $sumUnits = number_format($sumUnits, 2);
        echo "
          <tr>
            <td></td>
            <td></td>
            <td style='font-weight: bolder'>TOTAL</td>
            <td class='text-center'>{$sumUnits}</td>
          </tr>";
        ?>
      </tbody>
    </table>
  </div>
</div>
