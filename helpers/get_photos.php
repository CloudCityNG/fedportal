<?php
include_once(__DIR__ . '/databases.php');
include_once(__DIR__ . '/app_settings.php');

function get_photo($regNo = null, $pathOnly = null)
{

  $stmt = get_db()->prepare("SELECT nameofpic FROM pics WHERE personalno = ?");

  if (!$regNo) {

    if (session_status() === PHP_SESSION_NONE)  session_start();

    $regNo = $_SESSION['REG_NO'];
  }

  if ($stmt->execute([$regNo]) && $stmt->rowCount()) {

    $imagePath = 'photo_files/' . $stmt->fetch(PDO::FETCH_NUM)[0];
    $staticRootTrimmed = trim(STATIC_ROOT, "/\\");
    $staticRootPos = strpos(__DIR__, $staticRootTrimmed);
    $dirPathBeforeStaticRoot = substr(__DIR__, 0, $staticRootPos);

    if (file_exists($dirPathBeforeStaticRoot . $staticRootTrimmed . '/' . $imagePath)) {
      $imagePath = STATIC_ROOT . $imagePath;

    } else $imagePath = BLANK_IMAGE_PATH;

    return $pathOnly ? $imagePath : "<img src='$imagePath'/>";
  }

  return BLANK_IMAGE_PATH;
}
