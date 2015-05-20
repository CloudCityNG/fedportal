<form
  class="form-horizontal academic-session-form current-session-form"
  role="form" method="post"
  action="<?php echo STATIC_ROOT . 'admin_academics/academic_session/' ?>"
  >

  <input type="hidden"
         value="<?php echo $currentSession['id']; ?>"
         name="current_session[id]"/>

  <div class="alternative">
    <?php if ($alternative) {
      echo 'Academic session has ended but new session not set. You may edit current session or create new session.';
    }
    ?>
  </div>

  <fieldset>
    <div class="form-group">
      <label class="control-label col-sm-3" for="session">Session</label>

      <div class="col-sm-4">
        <input disabled class="form-control" maxlength="9"
               name="current_session[session]" id="session" required
               value="<?php echo $currentSession['session']; ?>">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-3" for=start_date>Start Date</label>

      <div class="col-sm-4">
        <div class="input-group date show-date-picker input-append">
          <input disabled class="form-control" maxlength="10"
                 name="current_session[start_date]"
                 required id="start_date"
                 value="<?php echo $currentSession['start_date']->format('d-m-Y'); ?>"
                 data-fv-date
                 data-fv-date-format="DD-MM-YYYY"
            />

              <span class="input-group-addon add-on">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-3" for=end_date>End Date</label>

      <div class="col-sm-4">
        <div class="input-group date show-date-picker">
          <input disabled class="form-control" maxlength="10"
                 name="current_session[end_date]"
                 required id="end_date"
                 value="<?php echo $currentSession['end_date']->format('d-m-Y'); ?>"
                 data-fv-date
                 data-fv-date-format="DD-MM-YYYY"/>

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
        </div>
      </div>
    </div>
  </fieldset>

  <div class="row">
    <div class="current-session-form-btn col-sm-4 col-sm-offset-3">
      <button class="btn btn-default" type="submit" name="current-session-form-submit">
        Update Current Session
      </button>
    </div>
  </div>
</form>
