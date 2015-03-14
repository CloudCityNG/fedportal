<?php

/**
 * Created by maneptha on 28-Feb-15.
 */

require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../models/AcademicSession.php');

class AcademicSessionController
{
  private static $LOG_NAME = 'ACADEMICS-ADMIN-SESSION-CONTROLLER';

  public function get()
  {
    $this->renderPage();
  }

  private function renderPage($oldNewSessionData = null, $postStatus = null)
  {
    $currentPage = [
      'title' => 'session',

      'link' => 'new-session'
    ];

    $current_session = $this->get_current_session();

    $link_template = __DIR__ . '/session-form.php';

    $pageJsPath = STATIC_ROOT . 'admin_academics/academic_session/js/session.min.js';

    require(__DIR__ . '/../home/container.php');

  }

  private function get_current_session()
  {
    $log = get_logger(self::$LOG_NAME);

    $current_session = [
      'id' => '',
      'session' => '',
      'start_date' => '',
      'end_date' => ''
    ];

    try {
      $current_session = AcademicSession::get_current_session();

      if (count($current_session)) {
        $current_session['current_session_not_found'] = '';
        return $current_session;

      } else {
        $current_session['current_session_not_found'] = 'current_session_not_found';
      }

    } catch (PDOException $e) {

      logPdoException($e, "Error while getting current session.", $log);
    }

    return $current_session;
  }

  public function post()
  {
    header("Content-Type: application/json");

    if (isset($_POST['create']) && $_POST['create']) {

      echo json_encode(self::create_session($_POST));
      return;
    }
  }

  private static function create_session($data)
  {
    $log = get_logger(self::$LOG_NAME);

    $data = $_POST['data'];

    try {
      if (AcademicSession::session_exists($data['session'])) {
        return ['exists' => true];

      }

      $academic_session = AcademicSession::create_session($data);
      $academic_session['created'] = true;

      return $academic_session;

    } catch (PDOException $e) {

      logPdoException($e, "Error occurred while creating new session.", $log);
    }

    return ['error' => true];
  }
}

$academic_session = new AcademicSessionController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $academic_session->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $academic_session->post();
}
