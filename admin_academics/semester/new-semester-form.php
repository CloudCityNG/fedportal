<form class="form-horizontal semester-form new-semester-form" role="form"
      method="post" action="<?php echo path_to_link(__DIR__) ?>"
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh">

  <fieldset>
    <legend class="clearfix">
      <span class="pull-left">New Semester</span>
    </legend>

    <div class="form-group">
      <label class="control-label col-sm-4" for="new-semester-number">Semester Number</label>

      <div class="col-sm-5">
        <select class="form-control" name="new_semester[number]"
                id="new-semester-number" required>
          <option value="">---</option>

          <option
            value="1" <?php echo ($oldNewSemester && $oldNewSemester['number'] == 1) ? 'selected' : ''; ?> >
            1st
          </option>

          <option
            value="2" <?php echo ($oldNewSemester && $oldNewSemester['number'] == 2) ? 'selected' : ''; ?> >
            2nd
          </option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for=new-start-date>Start Date</label>

      <div class="col-sm-5">
        <div class="input-group show-date-picker date">
          <input class="form-control" name="new_semester[start_date]" maxlength="10"
                 required id="new-start-date"
                 value="<?php echo $oldNewSemester ? $oldNewSemester['start_date'] : '' ?>"
                 data-fv-date data-fv-date-format="DD-MM-YYYY"/>

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for=new-end-date>End Date</label>

      <div class="col-sm-5">
        <div class="input-group date show-date-picker">
          <input class="form-control" name="new_semester[end_date]"
                 required id="new-end-date"
                 value="<?php echo $oldNewSemester ? $oldNewSemester['end_date'] : '' ?>"
                 data-fv-date data-fv-date-format="DD-MM-YYYY"/>

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for="new-semester-session">
        Session
      </label>

      <div class="col-sm-5">
        <input
          class="form-control semester-session" name="new_semester[session]"
          id="new-semester-session" maxlength="9"
          value="<?php echo $oldNewSemester ? $oldNewSemester['session'] : '' ?>"
          data-related-input-id="#new-semester-session-id">

        <input
          type="hidden" id="new-semester-session-id" name="new_semester[session_id]"
          value="<?php echo $oldNewSemester ? $oldNewSemester['session_id'] : '' ?>"/>
      </div>
    </div>
  </fieldset>

  <div class="row">
    <div class="semester-form-btn col-sm-5 col-sm-offset-4">
      <button class="btn btn-default" type="submit" name="new-semester-form-submit">
        Create New Semester
      </button>
    </div>
  </div>
</form>
