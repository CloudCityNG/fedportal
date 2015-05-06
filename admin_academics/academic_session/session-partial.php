<div class="panel panel-default current-session-panel">
  <div class="panel-heading">
    <h1 class="panel-title">
      Current Session
    </h1>
  </div>

  <div class="panel-body">

    <?php
    if ($postStatus && isset($postStatus['current_session'])) {

      $postStatus = $postStatus['current_session'];

      if ($postStatus['updated']) {
        $alertClass = 'alert-success';
        $status = 'Academic session successfully updated';

      } else {
        $alertClass = 'alert-danger';
        $status = 'Academic session failed to updated.';
      }

      echo "
              <div class='alert alert-dismissible {$alertClass}' role='alert'>
                <button type=button class=close data-dismiss=alert aria-label=Close>
                  <span aria-hidden=true>&times;</span>
                </button>

                <h5>{$status}</h5>
              </div> ";
    }

    if ($currentSession) {
      require __DIR__ . '/current-session-form.php';

    } else {
      echo 'Academic session has ended but new session not set.';
    }
    ?>

  </div>

  <div class="panel-footer">
       <span class="glyphicon glyphicon-edit current-session-edit-trigger"
             data-toggle="tooltip" title="Edit current session"
             id="session-form-edit-icon1"></span>
  </div>
</div>

<?php
if ($postStatus and isset($postStatus['new_session'])) {

  $postStatus = $postStatus['new_session'];

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
    <div class="new-session-form-btn col-sm-5 col-sm-offset-4">
      <button class="btn btn-default" type="submit" name="new-session-form-submit">
        Create New Session
      </button>
    </div>
  </div>
</form>
