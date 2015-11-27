<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once(__DIR__ . '/../../helpers/app_settings.php');

unset($_SESSION[STAFF_USER_SESSION_KEY]);
unset($_SESSION[USER_AUTH_SESSION_KEY]);

$login = STATIC_ROOT . 'admin_academics/login/';

header("Location: {$login}");
