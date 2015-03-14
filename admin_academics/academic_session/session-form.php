<div class="row academic-session-view">
  <div class="panel panel-default current-session-panel">
    <div class="panel-heading">
      <h1 class="panel-title">Current Session</h1>
    </div>

    <div class="panel-body">
      <form
        class="form-horizontal academic-session-form current-session-form <?php echo $current_session['current_session_not_found']; ?>"
        role="form" method="post"
        action="<?php echo STATIC_ROOT . 'admin_academics/academic_session/' ?>"
        >

        <input type="hidden"
               value="<?php echo $current_session['id']; ?>"
               name="current_session[id]"/>

        <fieldset>
          <div class="form-group">
            <label class="control-label col-sm-3" for="session">Session</label>

            <div class="col-sm-4">
              <input disabled class="form-control" maxlength="9"
                     name="current_session[session]" id="session" required
                     value="<?php echo $current_session['session']; ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-3" for=start_date>Start Date</label>

            <div class="col-sm-4">
              <div class="input-group date show-date-picker input-append">
                <input disabled class="form-control" maxlength="10"
                       name="current_session[start_date]"
                       required id="start_date"
                       value="<?php echo $current_session['start_date']; ?>"
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
                       value="<?php echo $current_session['end_date']; ?>"
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
              Edit Current Session
            </button>
          </div>
        </div>
      </form>
    </div>

    <div class="panel-footer">
       <span class="glyphicon glyphicon-edit current-session-edit-trigger"
             data-toggle="tooltip" title="Edit current session"
             id="session-form-edit-icon1"></span>
    </div>
  </div>

  <?php
  if ($postStatus) {

    if ($postStatus['posted']) {
      $alertClass = 'alert-success';
      $status = $postStatus['messages'][0];
      $messages = '';

    } else {
      $alertClass = 'alert-danger';
      $status = "New session not created for following reasons:";

      $messages = '';

      foreach ($postStatus['messages'] as $message) {
        $messages .= "<li>{$message}</li>\n";
      }
    }

    echo "
      <div class='alert alert-dismissible {$alertClass}' role='alert'>
        <button type=button class=close data-dismiss=alert aria-label=Close>
          <span aria-hidden=true>&times;</span>
        </button>

        <h5>{$status}</h5>

        <ol>
          {$messages}
        </ol>
      </div> ";
  }
  ?>

  <div class="col-sm-8">
    <form class="form-horizontal academic-session-form new-session-form"
          role="form" method="post"
          action="<?php echo STATIC_ROOT . 'admin_academics/academic_session/' ?>">

      <fieldset>
        <legend class="clearfix">
          <span class="pull-left">Create New Session</span>
        </legend>

        <div class="form-group">
          <label class="control-label col-sm-4" for="new-session">Session</label>

          <div class="col-sm-5">
            <input class="form-control" name="new_session[session]" id="new-session" required
                   value="<?php echo $oldNewSessionData ? $oldNewSessionData['session'] : '' ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-4" for=new-session-start-date>Start Date</label>

          <div class="col-sm-5">
            <div class="input-group show-date-picker date">
              <input class="form-control" name="new_session[start_date]"
                     required id="new-session-start-date"
                     value="<?php echo $oldNewSessionData ? $oldNewSessionData['start_date'] : '' ?>"
                     data-fv-date
                     data-fv-date-format="DD-MM-YYYY"/>

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-4" for=new-session-end-date>End Date</label>

          <div class="col-sm-5">
            <div class="input-group date show-date-picker">
              <input class="form-control" name="new_session[end_date]"
                     required id="new-session-end-date"
                     value="<?php echo $oldNewSessionData ? $oldNewSessionData['end_date'] : '' ?>"
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
        <div class="new-session-form-btn col-sm-4 col-sm-offset-4">
          <button class="btn btn-default" type="submit" name="new-session-form-submit">
            Create New Session
          </button>
        </div>
      </div>
    </form>
  </div>
</div>