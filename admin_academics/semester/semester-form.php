<div class="semester-view">

  <span id="two-most-recent-sessions" style="display: none;">
    <?php echo json_encode($two_most_recent_sessions) ?>
  </span>

  <div class="panel panel-default current-semester-panel">
    <div class="panel-heading">
      <h1 class="panel-title">Current Semester</h1>
    </div>

    <div class="panel-body">
      <form
        class="form-horizontal current-semester-form <?php echo $current_semester['current_semester_not_found'] ?>"
        role="form"
        data-fv-framework="bootstrap"
        data-fv-message="This value is not valid"
        data-fv-icon-valid="glyphicon glyphicon-ok"
        data-fv-icon-invalid="glyphicon glyphicon-remove"
        data-fv-icon-validating="glyphicon glyphicon-refresh">

        <input type="hidden" value="<?php echo $current_semester['id'] ?>" name="current_semester[id]"/>

        <fieldset>
          <div class="form-group">
            <label class="control-label col-sm-4" for="number">Semester Number</label>

            <div class="col-sm-5">
              <select disabled class="form-control" name="current_semester[number]" id="number" required>
                <option <?php echo $current_semester['number'] == 1 ? 'selected' : '' ?> value="1">
                  1st
                </option>

                <option <?php echo $current_semester['number'] == 2 ? 'selected' : '' ?> value="2">
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
                       value="<?php echo $current_semester['start_date']->format('d-m-Y') ?>"
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
                       value="<?php echo $current_semester['end_date']->format('d-m-Y') ?>"
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
              <input disabled class="form-control semester-session" required
                     name="current_semester[session][session]" maxlength="9"
                     id="current-semester-session" data-related-input-id="#current_semester-id"
                     value="<?php echo $current_semester['session']['session'] ?>">

              <input type="hidden" name="current_semester[session][id]" id="current_semester-id"
                     value="<?php echo $current_semester['session']['id'] ?>"/>
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
    </div>

    <div class="panel-footer">
       <span class="glyphicon glyphicon-edit current-semester-edit-trigger"
             data-toggle="tooltip" title="Edit semester"
             id="semester-form-edit-icon1"></span>
    </div>
  </div>

  <?php
  if ($postStatus and isset($postStatus['new_semester'])) {

    $postStatus = $postStatus['new_semester'];

    if ($postStatus['posted']) {
      $alertClass = 'alert-success';
      $status = $postStatus['messages'][0];
      $messages = '';

    } else {
      $alertClass = 'alert-danger';
      $status = "New semester not created for following reasons:";

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

  <div>
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
              id="new-semester-session" maxlength="9" required
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
  </div>
</div>
