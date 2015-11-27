<?php

require_once(__DIR__ . '/../../helpers/app_settings.php');

function adminAcademicsAuth()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  if (!isset($_SESSION[USER_AUTH_SESSION_KEY]) ||
    !isset($_SESSION[STAFF_USER_SESSION_KEY]) ||
    !sessionAgeValid(STAFF_USER_SESSION_KEY)) {
    header('location: ' . STATIC_ROOT . 'admin_academics/login/');
    exit;
  }
}

adminAcademicsAuth();
