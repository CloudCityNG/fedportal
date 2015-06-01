<?php

function studentDashboardSession()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once(__DIR__ . '/../../helpers/app_settings.php');

  $login = STATIC_ROOT . 'student_portal/login/';

  if (!isset($_SESSION[STUDENT_PORTAL_AUTH_KEY])) {
    header("location: $login");
    return;
  }

  if (!trim($_SESSION[STUDENT_PORTAL_AUTH_KEY])) {
    unset($_SESSION[STUDENT_PORTAL_AUTH_KEY]);
    header("location: $login");
    return;
  }

  require_once(__DIR__ . '/../../helpers/databases.php');

  $regNo = trim($_SESSION[STUDENT_PORTAL_AUTH_KEY]);

  $stmt = get_db()->prepare("SELECT COUNT(*) FROM pin_table WHERE number = ? ;");
  $stmt->execute([$regNo]);

  if (!$stmt->fetchColumn()) {
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
