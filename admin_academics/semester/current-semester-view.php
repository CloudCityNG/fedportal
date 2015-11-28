<div class="panel panel-default current-semester-panel">
  <div class="panel-heading">
    <h1 class="panel-title">Current Semester</h1>
  </div>

  <div class="panel-body">
    <?php
    if ($oldCurrentSemesterData || $current_semester) {
      renderPostStatus($postStatus, 'current_semester');

      require __DIR__ . '/current-semester-form.php';

    } else if ($semestersInCurrentSession) {
      require(__DIR__ . '/current-semester-select-update.php');

    } else {
      echo 'Semester or session not set';
    }
    ?>
  </div>

  <?php
  if ($oldCurrentSemesterData || $current_semester) {
    echo '<div class="panel-footer">
                <span class="glyphicon glyphicon-edit current-semester-edit-trigger"
                data-toggle="tooltip" title="Edit semester"
                id="semester-form-edit-icon1"></span>
            </div>';
  }
  ?>
</div>
