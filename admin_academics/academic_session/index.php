<?php

require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../models/AcademicSession.php');

class AcademicSessionController
{
  private static $LOG_NAME = 'ACADEMICS-ADMIN-SESSION-CONTROLLER';

  public function renderPage($oldNewSessionData = null, $postStatus = null)
  {
    $currentPage = [
      'title' => 'session',

      'link' => 'new-session'
    ];

    $current_session = $this->get_current_session();

    $link_template = __DIR__ . '/session-form.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/session.min.js');

    $pageCssPath = path_to_link(__DIR__ . '/css/session.min.css');

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
      $current_session = AcademicSession::getCurrentSession();

      if ($current_session) {
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
    if (isset($_POST['new-session-form-submit'])) {

      $newSessionData = $_POST['new_session'];

      $status = self::create_session($newSessionData);

      $oldNewSessionData = $status['posted'] ? null : $newSessionData;

      $postStatus['new_session'] = $status;

      $this->renderPage($oldNewSessionData, $postStatus);
      return;

    } else if (isset($_POST['current-session-form-submit'])) {

      $currentSessionData = $_POST['current_session'];

      $status['current_session'] = self::update_session($currentSessionData);

      $this->renderPage(null, $status);

    }
  }

  private static function create_session($data)
  {
    $log = get_logger(self::$LOG_NAME);

    $session = $data['session'];

    try {
      if (AcademicSession::session_exists_by_session($session)) {
        return [
          'posted' => false,

          'messages' => [
            $session . ' session already exists.'
          ]
        ];

      }

      if (AcademicSession::create_session($data)) {
        return [
          'posted' => true,

          'messages' => [$session . ' session successfully created.']
        ];
      }

    } catch (PDOException $e) {

      logPdoException($e, "Error occurred while creating new session.", $log);
    }

    return ['error' => true];
  }

  private static function update_session($data)
  {
    $log = get_logger(self::$LOG_NAME);

    try {
      $session = AcademicSession::update_session($data);

      if ($session) {
        return [
          'updated' => true
        ];
      }

    } catch (PDOException $e) {

      logPdoException($e, "Error while updating session", $log);
    }

    return [
      'updated' => false
    ];
  }
}

$academic_session = new AcademicSessionController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $academic_session->renderPage();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $academic_session->post();
}
