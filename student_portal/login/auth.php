<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/models/Pin.php');

function studentDashboardSession()
{
  if (session_status() === PHP_SESSION_NONE) session_start();

  $login = STATIC_ROOT . 'student_portal/login/';

  if (!isset($_SESSION[STUDENT_PORTAL_AUTH_KEY])) {
    header("location: $login");
    return;
  }

  $regNo = trim($_SESSION[STUDENT_PORTAL_AUTH_KEY]);

  if (!$regNo) {
    unset($_SESSION[STUDENT_PORTAL_AUTH_KEY]);
    header("location: $login");
    return;
  }

  if (!Pin::get(['number' => $regNo, '__exists' => true])) {
    unset($_SESSION[STUDENT_PORTAL_AUTH_KEY]);
    header("location: {$login}");
    return;
  }

  if (!sessionAgeValid(STUDENT_PORTAL_AUTH_KEY)) {
    header("location: {$login}");
    return;
  }
}

studentDashboardSession();
