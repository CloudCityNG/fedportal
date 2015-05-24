<?php

/**
 * This function will be called for every academic semester. It will
 *
 * @param string $session - the academic session code e.g 2014/2015
 * @param string|int $semesterNumber - the semester number, 1 or 2
 * @param array $semesterDataAndCourses
 * @param string $level - student's level in a particular session e.g ND2
 * @return string - a table rendered with student's courses, scores and grades as well
 * as session and semester information
 * @internal param array $currentLevelDept
 *
 */
function renderCoursesData($session, $semesterNumber, array $semesterDataAndCourses, $level)
{
  $semesterText = $semesterNumber == 1 ? "FIRST SEMESTER - ({$level}) ({$session})" : "SECOND SEMESTER - ({$level})";

  $tableStart = "
    <table class='table table-striped table-condense table-bordered student-transcript-table'>
        <caption class='transcript-display-table-caption'>{$semesterText}</caption>

      <thead>
        <tr>
          <th>S/N</th>
          <th>Course<br/>Code</th>
          <th>Course Title</th>
          <th>Credit<br/>Unit</th>
          <th class='student-courses-display-existing-score'>Score</th>
          <th>Grade</th>
          <th>Quality<br/>Point</th>
        </tr>
      </thead>

      <tbody>\n";

  $coursesTableBody = '';
  $count = 1;

  foreach ($semesterDataAndCourses['courses'] as $course) {
    $unit = number_format($course['unit'], 1);

    $coursesTableBody .= "
            <tr>
                <td>{$count}</td>
                <td>{$course['code']}</td>
                <td>{$course['title']}</td>
                <td>{$unit}</td>
                <td>{$course['score']}</td>
                <td>{$course['grade']}</td>
                <td>{$course['quality_point']}</td>
            </tr>\n
           ";

    $count++;
  }

  $coursesTableBody .= "
                <tr class='total-row'>
                    <td class='no-border'></td>
                    <td class='no-border'></td>
                    <td>TOTAL</td>
                    <td>{$semesterDataAndCourses['sum_units']}</td>
                    <td></td>
                    <td></td>
                    <td>{$semesterDataAndCourses['sum_points']}</td>
                </tr>
             </tbody>
          </table>

          <div class='gpa'>
            GPA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;{$semesterDataAndCourses['gpa']} <br/>
            CGPA&nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;{$semesterDataAndCourses['cgpa']}
          </div>";

  return $tableStart . $coursesTableBody;
}

?>

<hr/>

<?php
$student = $studentScoresData['student'];

echo "
    <hr/>

    <div class='student-courses-display-img-and-name media'>
      <a class='pull-left'>
        <img class='media-object' width='120px'
             src='{$student['photo']}'
             alt='{$student['names']}'/>
      </a>

      <div class='media-body'>
          <table class='table table-condense table-bordered'>
              <tbody>
                  <tr>
                      <th>NAMES</th> <td>{$student['names']}</td>
                  </tr>

                  <tr>
                      <th>REGISTRATION NO</th> <td>{$student['reg_no']}</td>
                  </tr>

                  <tr>
                      <th>DEPARTMENT</th> <td>{$student['dept_name']}</td>
                  </tr>

                  <tr>
                      <th>LEVEL</th> <td>{$student['level']}</td>
                  </tr>

                  <tr>
                      <th>YEAR OF ADMISSION</th> <td>{$student['academic_year']}</td>
                  </tr>
              </tbody>
          </table>
      </div>
    </div>
    ";
?>

<hr/>

<?php
foreach ($studentScoresData['sessions_semesters_courses_grades'] as $session => $sessionData) {

  foreach ($sessionData['semesters'] as $semesterNumber => $semesterDataAndCourses) {
    echo renderCoursesData(
      $session,
      $semesterNumber,
      $semesterDataAndCourses,
      $sessionData['current_level_dept']['level']
    );
  }
}
?>

<form action="" class="form-horizontal" role="form" id="transcript-download-form" method="post">

  <input type='hidden' value='<?php echo json_encode($studentScoresData) ?>' name="student-scores-data"/>

  <div class="form-group">
    <div class="student-transcript-download-form-btn col-sm-5 col-sm-offset-4">
      <button class="btn btn-info" type="submit" name="student-transcript-download-submit">Download Transcripts</button>
    </div>
  </div>
</form>
