<?php

function adminAcademicsAuth()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once(__DIR__ . '/../../helpers/app_settings.php');

  $login = STATIC_ROOT . 'admin_academics/login/';

  if (!isset($_SESSION['ADMIN-FINANCE'])) {
    header("location: {$login}");
    exit;
  }

  if (!trim($_SESSION['ADMIN-FINANCE'])) {
    header("location: {$login}");
    exit;
  }

  if (!sessionAgeValid('ADMIN-FINANCE')) {
    header("location: {$login}");
    exit;
  }
}

adminAcademicsAuth();
