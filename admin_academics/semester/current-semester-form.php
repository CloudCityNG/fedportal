<form method="post"
      class="form-horizontal current-semester-form"
      role="form"
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh">

  <input type="hidden" name="current_semester[id]"
         value="<?php echo $oldCurrentSemesterData ? $oldCurrentSemesterData['end_date'] : $current_semester['id'] ?>"/>

  <fieldset>
    <div class="form-group">
      <label class="control-label col-sm-4" for="number">Semester Number</label>

      <div class="col-sm-5">
        <select disabled class="form-control" name="current_semester[number]" id="number" required>
          <option <?php echo ($oldCurrentSemesterData && $oldCurrentSemesterData['number'] == 1) ? 'selected' : $current_semester['number'] == 1 ? 'selected' : '' ?>
            value="1">
            1st
          </option>

          <option <?php echo ($oldCurrentSemesterData && $oldCurrentSemesterData['number'] == 2) ? 'selected' : $current_semester['number'] == 2 ? 'selected' : '' ?>
            value="2">
            2nd
          </option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for=start_date>Start Date</label>

      <div class="col-sm-5">
        <div class="input-group date show-date-picker input-append">
          <input disabled class="form-control" name="current_semester[start_date]"
                 required id="start_date"
                 value="<?php echo $oldCurrentSemesterData ? $oldCurrentSemesterData['start_date'] : $current_semester['start_date']->format('d-m-Y') ?>"
                 data-fv-date
                 data-fv-date-format="DD-MM-YYYY"/>

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for=end_date>End Date</label>

      <div class="col-sm-5">
        <div class="input-group date show-date-picker input-append">
          <input disabled class="form-control date-picker" name="current_semester[end_date]"
                 required id="end_date"
                 value="<?php echo $oldCurrentSemesterData ? $oldCurrentSemesterData['end_date'] : $current_semester['end_date']->format('d-m-Y') ?>"
                 data-fv-date
                 data-fv-date-format="DD-MM-YYYY"/>

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for="current-semester-session">Session</label>

      <div class="col-sm-5">
        <input readonly class="form-control semester-session" required
               name="current_semester[session]" maxlength="9"
               id="current-semester-session" data-related-input-id="#current_semester-id"
               value="<?php echo $oldCurrentSemesterData ? $oldCurrentSemesterData['session'] : $current_semester['session']['session'] ?>">

        <input type="hidden" id="current_semester-id"
               name="current_semester[session_id]"
               value="<?php echo $oldCurrentSemesterData ? $oldCurrentSemesterData['session_id'] : $current_semester['session_id'] ?>"/>
      </div>
    </div>
  </fieldset>

  <div class="form-group">
    <div class="current-semester-form-btn col-sm-5 col-sm-offset-4">
      <button class="btn btn-default" type="submit" name="current-semester-form-submit">
        Edit Current Semester
      </button>
    </div>
  </div>
</form>
