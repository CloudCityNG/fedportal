<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/create-course/CreateCourseController.php');
require_once(__DIR__ . '/list-courses/ListCoursesController.php');

AdminAcademicsAuth::checkCapability('can_view_courses');

class CourseController
{

  /**
   * When a generic error is caught in a code block, log that error
   *
   * @param Exception $e
   * @param \Monolog\Logger $logger
   * @param string $message
   */
  protected static function logGeneralError(Exception $e, Monolog\Logger $logger, $message = '')
  {
    $logger->addInfo('Unknown Error: ' . $message);
    $logger->addInfo('Unknown Error: ' . $e->getMessage());
  }
}

$query = explode('&', $_SERVER['QUERY_STRING']);

switch ($query[0]) {

  case 'list-courses': {
    AdminAcademicsAuth::checkCapability('can_list_courses');
    $courseList = new ListCoursesController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $courseList->get();

    }
    break;
  }

  case 'create-course': {
    AdminAcademicsAuth::checkCapability('can_create_course');
    $createCourse = new CreateCourseController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $createCourse->renderPage(['query' => $query]);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $createCourse->post();
    }
    break;
  }

  default:
    $home = path_to_link(__DIR__ . '/../home');
    header("Location: {$home}");
}
