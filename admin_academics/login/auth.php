<?php

function adminAcademicsAuth()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once(__DIR__ . '/../../helpers/app_settings.php');

  if (!isset($_SESSION[STAFF_USER_KEY]) || !sessionAgeValid(STAFF_USER_KEY)) {
    header('location: ' . STATIC_ROOT . 'admin_academics/login/');
    exit;
  }
}

adminAcademicsAuth();
