<?php

function adminAcademicsAuth()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once(__DIR__ . '/../../helpers/app_settings.php');

  $login = STATIC_ROOT . 'admin_general/login/';

  if (!isset($_SESSION[STAFF_USER_KEY]) || !sessionAgeValid(STAFF_USER_KEY)) {
    header("location: {$login}");
    exit;
  }
}

adminAcademicsAuth();
