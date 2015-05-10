<form id="student-transcript-query-form"
      class="form-horizontal"
      method="post"
      role="form"
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh">

  <fieldset>
    <legend>Input student Registration Number</legend>

    <div>
      <?php AssessmentTranscriptController::renderPostStatus($postStatus); ?>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for="reg-no">Registration Number</label>

      <div class="col-sm-7">
        <input class="form-control" id="reg-no" required
               name="student-transcript-query[reg-no]"
               value="<?php echo $oldStudentTranscriptQueryData ? $oldStudentTranscriptQueryData['reg-no'] : '' ?>"
               placeholder="Student registration Number"/>
      </div>
    </div>
  </fieldset>

  <div class="form-group">
    <div class="student-transcript-query-form-btn col-sm-5 col-sm-offset-4">
      <button class="btn btn-default" type="submit" name="student-transcript-query-submit">Submit</button>
    </div>
  </div>
</form>

<?php
if ($studentScoresData) require(__DIR__ . '/transcript-display.php');
?>
