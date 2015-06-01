<div class="already-registered">
  <h4>
    You are signed up for courses for <?php echo $semester_text ?> semester
    Please print course form if you have not done so.
  </h4>
  <span class='printer-friendly'>Click here for printer friendly view.</span>
</div>

<div class="view-print-courses">

  <h4 class="text-center college-name"><?php echo strtoupper(SCHOOL_NAME) ?></h4>

  <h4 class="text-center">
    <strong>COURSE REGISTRATION FORM</strong> - DEPARTMENT OF <?php echo $dept_name; ?>
  </h4>

  <table class="img-and-name">
    <tbody>
      <tr>
        <td>
          <img src="<?php echo get_photo($regNo, true); ?>" alt="<?php echo $student['names']; ?>"/>
        </td>

        <td class="names">
          <div>
            <p>
              <strong>
                NAME OF STUDENT:&nbsp;&nbsp;
              </strong>
              <?php echo strtoupper($student['names']); ?>
            </p>

            <p>
              <strong>REGISTRATION NO:&nbsp;&nbsp;</strong> <?php echo $regNo; ?>
            </p>

            <p>
              <strong>
                LEVEL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </strong>
              <?php echo $currentLevel; ?>
            </p>

            <p>
              <strong>
                SESSION:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </strong>
              <?php echo "{$semester_text} semester {$academicYear} session"; ?>
            </p>
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <div class="row">
    <table class="table table-striped table-condensed table-bordered view-print-course-table">

      <thead>
        <tr>
          <th>#</th>
          <th>Course Code</th>
          <th>Course Title</th>
          <th>Credit Unit</th>
          <th>Lecturer Sign</th>
        </tr>
      </thead>

      <tbody>
        <?php
        $course_seq = 1;

        foreach ($course_data as $courses) {
          $code = $courses['code'];
          $title = $courses['title'];
          $unit = sprintf('%.2f', $courses['unit']);

          echo "<tr>\n" .

            "<td>$course_seq</td>\n" .

            "<td>$code</td>\n" .

            "<td>$title</td>\n" .

            "<td class='text-center'>$unit</td>\n" .

            '<td></td>' .

            "</tr>";

          $course_seq++;
        }
        ?>
      </tbody>
    </table>

    <table class="table table-bordered signature-table">
      <tbody>
        <tr>
          <th>HOD'S CONFIRMATION</th>

          <td></td>

          <th>ACADEMIC ADVISER</th>

          <td></td>

          <th>DEPUTY RECTOR</th>

          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<p class="back-to-main" style="display: none;">Back to main</p>
