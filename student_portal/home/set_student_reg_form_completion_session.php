<?php

/**
 * Set a session which will be read to determine if registration action (bio data, courses) succeeds or fails
 * @param String $code - The success/error code
 * @param String $message - Message that will be displayed to user
 */

function set_student_reg_form_completion_session1($code, $message) {
  if (session_status() == PHP_SESSION_NONE) session_start();

  $_SESSION['STUDENT-REG-FORM-REGISTRATION'] = json_encode([$code => ['message' => $message]]);
}
