<?php
/**
 * Created by maneptha on 25-Feb-15.
 */

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['FINANCE'])) {

  require_once(__DIR__ . '/../../helpers/app_settings.php');

  $login = STATIC_ROOT . 'admin_finance/login/';

  header("location: {$login}");
}
