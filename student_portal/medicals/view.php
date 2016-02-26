<?php
if (Medicals::get(['reg_no' => $_SESSION[STUDENT_PORTAL_AUTH_KEY], '__exists' => true])) {
  echo '<div style="margin-top: 0" class="alert h3 alert-success" role="alert">
      Your medical record exists.
      <div>Kindly contact admin if you need to make changes.</div>
  </div>';
} else require(__DIR__ . '/form.php');
