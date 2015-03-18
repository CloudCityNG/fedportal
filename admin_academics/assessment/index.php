<?php

/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 17-Mar-15
 * Time: 4:00 PM
 */
require_once(__DIR__ . '/../../helpers/databases.php');

class AssessmentController
{
  public static function renderPage()
  {
    $currentPage = [
      'title' => 'assessment',

      'link' => 'enter-grades'
    ];

    $link_template = __DIR__ . '/grade-student-form.php';

    require(__DIR__ . '/../home/container.php');
  }

  public static function post()
  {
    if (isset($_POST['reg-no-submit'])) {
      $reg_no = trim($_POST['reg-no']);

      if ($reg_no) {
        print_r($_POST);
      }
    }
  }
}

$assessment = new AssessmentController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $assessment->renderPage();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $assessment->post();
}
