<?php

include_once(__DIR__ . '/databases.php');

include_once(__DIR__ . '/app_settings.php');

function get_photo($reg_no = null, $path_only = null)
{
  $db = get_db();

  $stmt = $db->prepare("SELECT nameofpic FROM pics WHERE personalno = ?");

  if (!$reg_no) {

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $reg_no = $_SESSION['REG_NO'];
  }

  $stmt->execute([$reg_no]);

  if ($stmt->rowCount()) {

    $image_path = STATIC_ROOT . 'photo_files/' . $stmt->fetch(PDO::FETCH_NUM)[0];

    return $path_only ? $image_path : "<img src='$image_path'/>";
  }

  return '';
}
