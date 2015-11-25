<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

unset($_SESSION[STAFF_USER_KEY]);

require_once(__DIR__ . '/../../helpers/app_settings.php');

$login = STATIC_ROOT . 'admin_academics/login/';

header("Location: {$login}");
