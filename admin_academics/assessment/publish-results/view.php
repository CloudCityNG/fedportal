<span style="display: none" id="tenMostRecentSemesters-container">
  <?php echo json_encode($tenMostRecentSemesters) ?>
</span>

<form id="semester-course-query-form"
      class="form-horizontal"
      method="post"
      role="form"
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh">

  <fieldset>
    <legend>Publish Scores So Students Can See Them</legend>

    <div>
      <?php PublishResultsController::renderPostStatus($postStatus); ?>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for="semester">Semester</label>

      <div class="col-sm-7">
        <input class="form-control semester" required
               placeholder="Type session e.g 2012/2013 to select semesters"
               name="semester-course-query[semester]" maxlength="24"
               id="semester" data-related-input-id="#semester-id"
               value="<?php echo $oldSemesterCourseQueryData ? $oldSemesterCourseQueryData['semester'] : '' ?>"

               data-fv-regexp
               data-fv-regexp-regexp="\d{4}/\d{4} - \d[stnd]{2} semester"
               data-fv-regexp-message="Please enter a pattern such as 2012/2013..."
          >

        <input type="hidden" id="semester-id"
               name="semester-course-query[semester_id]"
               value="<?php echo $oldSemesterCourseQueryData ? $oldSemesterCourseQueryData['semester_id'] : '' ?>"/>
      </div>
    </div>

    <div class="form-group">
      <label for="level" class="control-label col-sm-4">Level</label>

      <div class="col-sm-7">
        <select class="form-control" name="semester-course-query[level]" id="level" required>
          <option value="">----</option>
          <?php
          foreach ($academicLevels as $academicLevel) {
            echo "<option value='{$academicLevel['code']}'>{$academicLevel['description']}</option>";
          }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="department" class="control-label col-sm-4">Department</label>

      <div class="col-sm-7">
        <select class="form-control" name="semester-course-query[department]" id="department" required>
          <option value="">----</option>
          <?php
          foreach ($academicDepartments as $academicDepartment) {
            echo "<option value='{$academicDepartment['code']}'>{$academicDepartment['description']}</option>";
          }
          ?>
        </select>
      </div>
    </div>
  </fieldset>

  <div class="form-group">
    <div class="semester-course-form-btn col-sm-5 col-sm-offset-4">
      <button class="btn btn-default" type="submit" name="semester-level-department-courses-submit">Submit</button>
    </div>
  </div>
</form>

<?php
if ($coursesToClient) {
  require __DIR__ . '/courses-publish-display-input.php';
}
?>

