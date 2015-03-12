<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once(__DIR__ . '/../../helpers/app_settings.php');

$login = STATIC_ROOT . 'student_portal/login/';

if (!isset($_SESSION['REG_NO']) || (trim($_SESSION['REG_NO']) === '')) {
  header("location: $login");
  return;
}

require_once(__DIR__ . '/../../helpers/databases.php');

$db = get_db();

$reg_no = trim($_SESSION['REG_NO']);

$stmt = $db->prepare("select COUNT(*) from pin_table WHERE number = ? ;");
$stmt->execute([$reg_no]);

if (!$stmt->fetchColumn()) {
  header("location: $login");
  return;
}
