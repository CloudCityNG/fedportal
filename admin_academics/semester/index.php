<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../models/Semester.php');
require_once(__DIR__ . '/../models/AcademicSession.php');

class SemesterController
{
  private static $LOG_NAME = 'ACADEMICS-ADMIN-SEMESTER-CONTROLLER';

  private static function updateSemester(array $post)
  {
    $returnedVal = ['success' => false];

    if (self::validatePost($post)) {

      $log = get_logger(self::$LOG_NAME);

      try {

        if (Semester::update($post)) {
          $returnedVal = ['success' => true];
        }

      } catch (PDOException $e) {

        logPdoException($e, "DB error occurred while updating semester", $log);
      }

    }

    return $returnedVal;
  }

  private static function validatePost($post)
  {
    $valid = Semester::validateSessionIdColumn($post);

    if (!$valid['valid']) {
      return $valid['messages'];
    }

    $valid = Semester::validateDates($post);

    if (!$valid['valid']) {
      return $valid['messages'];
    }

    $valid = Semester::validateNumberColumn($post);

    if (!$valid['valid']) {
      return $valid['messages'];
    }

    return true;
  }

  public function post()
  {
    if (isset($_POST['new-semester-form-submit'])) {
      $newSemester = $_POST['new_semester'];

      $status = self::createNewSemester($newSemester);

      $oldNewSemesterData = $status['posted'] ? null : $newSemester;

      $postStatus['new_semester'] = $status;

      $this->renderPage($oldNewSemesterData, $postStatus);
    }
  }

  private static function createNewSemester($post)
  {
    $valid = self::validatePost($post);

    if ($valid !== true) {
      return [
        'posted' => false,

        'messages' => $valid
      ];
    }

    $log = get_logger(self::$LOG_NAME);

    if (isset($post['session'])) {
      unset($post['session']);
    }

    try {
      $semester = Semester::create($post);

      if ($semester) {
        $log->addInfo('New semester successfully created. Semester is: ', $semester);

        $number = $semester['number'] == 1 ? '1st' : '2nd';

        return [
          'posted' => true,

          'messages' => [
            "{$number} semester for {$semester['session']['session']} session successfully created."
          ]
        ];
      }


    } catch (PDOException $e) {
      logPdoException(
        $e,
        "Error occurred while creating new semester.",
        $log);
    }

    return [
      'posted' => false,

      'messages' => ['Database error. Unable to create semester']
    ];
  }

  /**
   * @param array|null $oldNewSemester
   * @param array|null $postStatus
   */
  public function renderPage(array $oldNewSemester = null, array $postStatus = null)
  {
    $current_semester = self::get_current_semester();

    $two_most_recent_sessions = self::get_two_most_recent_session();

    $currentPage = [
      'title' => 'semester',

      'link' => 'new-semester'
    ];

    $link_template = __DIR__ . '/semester-partial.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/semester.min.js');

    $pageCssPath = path_to_link(__DIR__ . '/css/semester.min.css');

    require(__DIR__ . '/../home/container.php');
  }

  private static function get_current_semester()
  {
    $log = get_logger(self::$LOG_NAME);

    try {
      $semester = Semester::getCurrentSemester();

      if ($semester) {
        return $semester;
      }
    } catch (PDOException $e) {
      logPdoException($e, 'Error occurred while getting current semester', $log);
    }

    return null;
  }

  private static function get_two_most_recent_session()
  {
    $log = get_logger(self::$LOG_NAME);

    $academic_sessions = [];

    try {
      $academic_sessions = AcademicSession::get_two_most_recent_sessions();

      if ($academic_sessions) {
        $log->addInfo("Academic session successfully retrieved: ", $academic_sessions);

        $academic_sessions = array_map(function ($a_session) {
          $a_session['label'] = $a_session['session'];
          $a_session['value'] = $a_session['session'];
          return $a_session;
        }, $academic_sessions);
      }

    } catch (PDOException $e) {

      logPdoException(
        $e,
        'Error occurred while retrieving the two most recent academic sessions',
        $log);
    }

    return $academic_sessions;
  }

  private function transform_date($val)
  {
    return implode('-', array_reverse(explode('-', $val)));
  }
}

$semester = new SemesterController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $semester->renderPage();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $semester->post();
}
