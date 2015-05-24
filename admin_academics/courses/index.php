<?php
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

require_once(__DIR__ . '/../models/AcademicLevels.php');

class CoursesController
{
  private static $LOG_NAME = 'ACADEMIC-ADMIN-COURSES-CONTROLLER';

  public function get()
  {
    header("Content-Type: application/json");

    if (isset($_GET['initial']) && $_GET['initial']) {
      echo json_encode([
        'template' => file_get_contents(__DIR__ . '/courses-view.mustache'),

        'context' => ['levels' => AcademicLevels::get_all_levels()]
      ]);

    } else {
      echo json_encode([]);
    }
  }

  public function post()
  {

  }
}

$controller = new CoursesController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $controller->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $controller->post();
}
