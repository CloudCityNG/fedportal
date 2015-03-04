<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 05-Feb-15
 * Time: 9:21 PM
 */

function set_student_reg_form_completion_session($code, $message) {
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  $FORM_COMPLETION_SESSION_KEY = 'STUDENT-REG-FORM-REGISTRATION';

  $_SESSION[$FORM_COMPLETION_SESSION_KEY] = json_encode([
    $code => ['message' => $message]
  ]);
}