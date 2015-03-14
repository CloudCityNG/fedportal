<?php

/**
 * Created by maneptha on 24-Feb-15.
 */
require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../models/Semester.php');
require_once(__DIR__ . '/../models/AcademicSession.php');

class SemesterController
{
  private static $LOG_NAME = 'ACADEMICS-ADMIN-SEMESTER-CONTROLLER';

  public function renderPage()
  {
    $current_semester = self::get_current_semester();

    $two_most_recent_sessions = self::get_two_most_recent_session();

    $currentPage = [
      'title' => 'semester',

      'link' => 'new-semester'
    ];

    $link_template = __DIR__ . '/semester-form.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/semester.min.js');

    $pageCssPath = path_to_link(__DIR__ . '/css/semester.min.css');

    require(__DIR__ . '/../home/container.php');
  }

  public function post()
  {
    header("Content-Type: application/json");

    if (isset($_POST['newSemester']) && $_POST['newSemester']) {
      echo json_encode(self::create_new_semester($_POST['data']));
    }
  }

  private static function validatePost($post)
  {
    $dateRe = "/^\d{2}-\d{2}-\d{4}$/";

    return isset($post['number']) &&
           isset($post['session_id']) &&
           isset($post['start_date']) &&
           isset($post['end_date']) &&
           preg_match($dateRe, $post['start_date']) &&
           preg_match($dateRe, $post['end_date']) &&
           preg_match("/^\d+$/", $post['session_id']) &&
           preg_match("/^[12]$/", $post['number']);
  }

  private function transform_date($val)
  {
    return implode('-', array_reverse(explode('-', $val)));
  }

  private function get_context()
  {
    return [
      'current_semester' => self::get_current_semester(),

      'sessions' => self::get_two_most_recent_session()
    ];

  }

  private static function get_two_most_recent_session()
  {
    $log = get_logger(self::$LOG_NAME);

    $academic_sessions = [];

    try {
      $academic_sessions = AcademicSession::get_two_most_recent_sessions();

      if (count($academic_sessions)) {
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

  private static function get_current_semester()
  {
    $log = get_logger(self::$LOG_NAME);

    try {
      $semester = Semester::get_current_semester();

      if ($semester) {
        $semester['current_semester_not_found'] = '';

        return $semester;
      }
    } catch (PDOException $e) {
      logPdoException($e, 'Error occurred while getting current semester', $log);
    }

    $semester['current_semester_not_found'] = 'current_semester_not_found';

    return $semester;
  }

  private static function create_new_semester($post)
  {
    $log = get_logger(self::$LOG_NAME);

    if (!self::validatePost($post)) {
      $log->addWarning("Data for creating new semester invalid: ", $post);

      return ['invalidPostData' => true];
    }

    $returnedVal = ['created' => false];

    if (isset($post['session'])) {
      unset($post['session']);
    }

    try {
      $semester = Semester::create($post);

      $returnedVal = $semester;

      $returnedVal['created'] = true;

      $log->addInfo('New semester successfully created. Semester is: ', $semester);

    } catch (PDOException $e) {
      logPdoException(
        $e,
        "Error occurred while creating new semester.",
        $log);
    }

    return $returnedVal;
  }

  private static function update_semester($post)
  {
    $returnedVal = ['success' => false];

    $post = $_POST;

    if (self::validatePost($post)) {

      $db = get_db();

      $log = get_logger(self::$LOG_NAME);

      $post['start_date'] = self::transform_date($post['start_date']);
      $post['end_date'] = self::transform_date($post['end_date']);

      $query = "UPDATE semester SET
                number = :number,
                start_date = :start_date,
                end_date = :end_date
                WHERE id = :_id";

      $log->addInfo(
        "About to update semester with query $query and query param: ",
        $post
      );

      try {
        $stmt = $db->prepare($query);
        $stmt->execute($post);

        $returnedVal = ['success' => true];

        $log->addInfo("semester successfully updated.");

      } catch (PDOException $e) {

        logPdoException($e, "Error occurred while updating semester", $log);
      }

    }

    return $returnedVal;
  }
}

$semester = new SemesterController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $semester->renderPage();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $semester->post();
}