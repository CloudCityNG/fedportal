<?php
function setStudentLoginSession($regNo)
{
  if (session_status() === PHP_SESSION_NONE) session_start();

  session_regenerate_id();
  unset($_SESSION[USER_AUTH_SESSION_KEY]);
  $_SESSION['REG_NO'] = $regNo;
  $_SESSION['LAST-ACTIVITY-REG_NO'] = time();
  session_write_close();
  header('location: ' . STATIC_ROOT . 'student_portal/home/');
}

