<?php
if (EduHistory::get(['reg_no' => $reg_no, '__exists' => true])) {
  echo '<div style="margin-top: 0" class="alert h3 alert-success" role="alert">
      Record of your education history exists.
      <div>Kindly contact admin if you need to make changes.</div>
  </div>';
} else require(__DIR__ . '/form.php');
