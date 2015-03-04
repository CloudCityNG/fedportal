<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once(__DIR__ . '/helpers/app_settings.php');

unset($_SESSION['REG_NO']);

$home = STATIC_ROOT . 'index.php';

header("Location: $home");
