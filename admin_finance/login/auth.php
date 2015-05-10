<?php

function adminFinanceSessionAuth()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once(__DIR__ . '/../../helpers/app_settings.php');

  $login = STATIC_ROOT . 'admin_finance/login/';

  if (!isset($_SESSION['ADMIN-FINANCE'])) {

    header("location: {$login}");
    return;
  }

  if (!trim($_SESSION['ADMIN-FINANCE'])) {
    header("location: {$login}");
    return;
  }

  if (!sessionAgeValid('ADMIN-FINANCE')) {
    header("location: {$login}");
    return;
  }
}

adminFinanceSessionAuth();
