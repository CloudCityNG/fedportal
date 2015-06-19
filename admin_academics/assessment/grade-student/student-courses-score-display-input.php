<hr/>

<div class="student-course-score-form-container">

  <?php
  $student = $studentCoursesData['student'];

  echo "
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
                    <th>COURSES FOR</th> <td>{$student['semester']}</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
  ";
  ?>

  <form id="student-course-score-form"
        class="form-horizontal student-course-score-form"
        method="post"
        role="form"
        data-fv-trigger="blur"
        data-fv-framework="bootstrap"
        data-fv-message="This value is not valid"
        data-fv-icon-valid="glyphicon glyphicon-ok"
        data-fv-icon-invalid="glyphicon glyphicon-remove"
        data-fv-icon-validating="glyphicon glyphicon-refresh">

    <fieldset>
      <legend>
        Courses Student Registered for in "<?php echo $student['semester'] ?>" <br/>
        Enter score for each course.
      </legend>

      <table class="table table-striped table-condense table-bordered student-course-score-form-table">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Code</th>
            <th>Title</th>
            <th>Unit</th>
            <th style="width: 20px;">Existing Score</th>
            <th style="width: 142px;">New Score</th>
            <th>Grade</th>
          </tr>
        </thead>

        <tbody>
          <?php
          $count = 1;

          foreach ($studentCoursesData['courses'] as $course) {

            $existingScore = $course['score'];
            $alreadyGradedDisabled = $existingScore ? 'disabled' : '';
            $alreadyGradedInputClass = $existingScore ? 'already-graded' : '';
            $alreadyGradedTdClass = $existingScore ? 'already-graded-td' : '';

            $editTrigger = $existingScore ?
              "<span class='glyphicon glyphicon-edit course-score-edit-trigger'></span>" : '';

            $viewOnlyTrigger = $existingScore ?
              "<span style='display: none;' class='glyphicon glyphicon-eye-open course-score-view-only-trigger'></span>" : '';

            $courseId = $course['id'];

            echo "
            <tr>
                <td class='seq'>{$count}</td>
                <td class='code'>{$course['code']}</td>
                <td class='title'>{$course['title']}</td>
                <td class='unit'>{$course['unit']}</td>

                <td class='td-existing-score {$alreadyGradedTdClass}'>
                  <input disabled value='{$existingScore}' class='form-control existing-score' />
                </td>

                <td class='fresh-score'>
                    <input class='form-control course-score {$alreadyGradedInputClass}'
                        name='student-courses[{$courseId}]' maxlength='6'
                        value='{$existingScore}' data-existing-score='{$existingScore}' {$alreadyGradedDisabled}

                        data-fv-regexp
                        data-fv-regexp-regexp='^\d{1,3}(?:\.\d{0,2})?$'
                        data-fv-regexp-message='Score must be between 0.00 and 100.00'

                        data-fv-between
                        data-fv-between-message='Score must be between 0.00 and 100.00'
                        data-fv-between-min='0'
                        data-fv-between-max='100'
                      />

                      {$editTrigger} {$viewOnlyTrigger}
                </td>

                <td class='grade'>{$course['grade']}</td>
            </tr>
           ";
            $count++;
          }
          ?>
        </tbody>
      </table>
    </fieldset>

    <div class="form-group">
      <div class="col-sm-5 col-sm-offset-4">
        <div class="btn-group">
          <button class="btn btn-default" type="submit" name="student-course-raw-score-form-submit">
            Submit
          </button>
          <button class="btn btn-default" type="button" id="student-course-score-form-reset-btn">Reset</button>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Modal -->
<div class="modal fade" id="current-semester-form-modal" tabindex="-1" role="dialog"
     aria-labelledby="current-semester-form-modal-title">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

        <h4 class="modal-title" id="current-semester-form-modal-title">Grade Student</h4>
      </div>
      <div class="modal-body" id="current-semester-form-modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button style="display: none;" type="button" class="btn btn-primary">Go ahead</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal form for when data is valid-->
<script type="text/template" id="scores-input-valid-form-template">
  <form method="post">
    <table class="table table-striped table-condense table-bordered student-course-score-form-table">
      <caption class="student-course-score-form-caption modal-body-caption">
        The following courses will be updated with scores
      </caption>

      <thead>
        <tr>
          <th>S/N</th>
          <th>Code</th>
          <th>Title</th>
          <th>Unit</th>
          <th style="width: 20px;">Old Score</th>
          <th style="width: 142px;">New Score</th>
          <th>Grade</th>
        </tr>
      </thead>

      <tbody>
      </tbody>
    </table>

    <?php
    $studentCoursesDataJSONEncoded = json_encode($studentCoursesData);
    echo "<input type='hidden' value='{$studentCoursesDataJSONEncoded}' name='student-courses-data' />";
    ?>

    <div style="text-align: center">
      <button class="btn btn-success" type="submit" name="student-course-score-form-submit">Ok</button>
    </div>
  </form>
</script>
