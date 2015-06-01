<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once(__DIR__ . '/../../helpers/app_settings.php');

$login = STATIC_ROOT . 'student_portal/login/';

unset($_SESSION[STUDENT_PORTAL_AUTH_KEY]);

header("location: {$login}");
