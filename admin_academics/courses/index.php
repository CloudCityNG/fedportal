<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../login/auth.php');
require(__DIR__ . '/add-course/AddCourse.php');

switch ($_SERVER['QUERY_STRING']) {
  case 'add-course': {
    $addCourse = new AddCourseController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $addCourse->renderPage();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $addCourse->post();
    }
    break;
  }

  default:
    $home = path_to_link(__DIR__ . '/../home');
    header("Location: {$home}");
}
