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

<table class="table table-striped table-condense table-bordered student-transcript-table">
  <thead>
    <tr>
      <th>S/N</th>
      <th>Code</th>
      <th>Title</th>
      <th>Unit</th>
      <th class="student-courses-display-existing-score">Score</th>
      <th>Grade</th>
    </tr>
  </thead>

  <tbody>
    <?php
    $count = 1;

    foreach ($studentScoresData['courses'] as $course) {
      echo "
            <tr>
                <td>{$count}</td>
                <td>{$course['code']}</td>
                <td>{$course['title']}</td>
                <td>{$course['unit']}</td>
                <td>{$course['score']}</td>
                <td>{$course['grade']}</td>
            </tr>
           ";
      $count++;
    }
    ?>
  </tbody>
</table>

<form action="" class="form-horizontal" role="form" id="transcript-download-form" method="post">

  <input type='hidden' value='<?php echo json_encode($studentScoresData) ?>' name="student-scores-data"/>

  <div class="form-group">
    <div class="student-transcript-download-form-btn col-sm-5 col-sm-offset-4">
      <button class="btn btn-info" type="submit" name="student-transcript-download-submit">Download Transcripts</button>
    </div>
  </div>
</form>
