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
