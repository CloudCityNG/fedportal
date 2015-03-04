<?php

include_once(__DIR__ . '/app_settings.php');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$home = STATIC_ROOT . 'index.php';

if (!isset($_SESSION['REG_NO']) || (trim($_SESSION['REG_NO']) === '')) {
  header("location: $home");
  return;
}

include_once(__DIR__ . '/databases.php');

$db = get_db();

$reg_no = trim($_SESSION['REG_NO']);

$stmt = $db->query("select COUNT(*) from pin_table WHERE number = '$reg_no' ;");

if (!$stmt->fetchColumn()) {
  header("location: $home");
  return;
}
