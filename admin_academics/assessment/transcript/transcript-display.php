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
                      <th>YEAR OF ADMISSION</th> <td>{$student['admission_session']}</td>
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
    echo StudentCoursesUtilities::renderCoursesData(
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
