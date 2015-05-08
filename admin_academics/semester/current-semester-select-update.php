<div style="margin-bottom: 30px;">
  Current semester may have ended or a semester may not have been set.<br/>
  Click to extend an existing semester. You may create a new semester under <strong>'New Semester'</strong>
</div>

<div>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>S/N</th>
        <th>Semester Number</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Session</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <?php
      $count = 1;

      foreach ($semestersInCurrentSession as $semesterInCurrentSession) {
        $semesterNumber = Semester::renderSemesterNumber($semesterInCurrentSession['number']);
        $data = json_encode($semesterInCurrentSession);
        echo "
            <tr>
                <td>{$count}</td>
                <td>{$semesterNumber}</td>
                <td>{$semesterInCurrentSession['start_date']->format('d-M-Y')}</td>
                <td>{$semesterInCurrentSession['end_date']->format('d-M-Y')}</td>
                <td>{$currentSession['session']}</td>
                <td>
                    <span class='glyphicon glyphicon-edit current-semester-select-update-trigger'></span>
                    <span style='display: none'>{$data}</span>
                </td>
              </tr>
             ";
        $count++;
      }
      ?>
    </tbody>
  </table>
</div>

<form method="post"
      class="form-horizontal current-semester-select-update-form"
      role="form"
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh"
      style="display: none">

  <input type="hidden" name="current_semester[id]" id="current-semester-select-update-id"/>

  <fieldset>
    <div class="form-group">
      <label class="control-label col-sm-4" for="number">Semester Number</label>

      <div class="col-sm-5">
        <select class="form-control" name="current_semester[number]" id="number" required>
          <option value="1">
            1st
          </option>

          <option value="2">
            2nd
          </option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-4" for=start_date>Start Date</label>

      <div class="col-sm-5">
        <div class="input-group date show-date-picker input-append">
          <input class="form-control" name="current_semester[start_date]"
                 required id="start_date"
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
          <input class="form-control date-picker" name="current_semester[end_date]"
                 required id="end_date"
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
               value="<?php echo $currentSession['session'] ?>"
          >

        <input type="hidden" id="current_semester-id"
               name="current_semester[session_id]"
               value="<?php echo $currentSession['id'] ?>"
          />
      </div>
    </div>
  </fieldset>

  <div class="form-group">
    <div class="col-sm-5 col-sm-offset-4">
      <div class="row">
        <div class="col-sm-6">
          <button class="btn btn-default" type="submit" name="current-semester-form-submit">
            Update Semester
          </button>
        </div>

        <div class="col-sm-6">
          <button class="btn btn-default" type="button" id="current-semester-select-update-clear-btn">
            Clear
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
