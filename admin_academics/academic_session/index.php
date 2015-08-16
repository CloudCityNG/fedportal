<?php
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../models/AcademicSession.php');
require_once(__DIR__ . '/../Utilities.php');

class AcademicSessionController
{
  public function post()
  {
    if (isset($_POST['new-session-form-submit'])) {

      $newSessionData = $_POST['new_session'];

      $status = self::createSession($newSessionData);

      $oldNewSessionData = $status['posted'] ? null : $newSessionData;

      $postStatus['new_session'] = $status;

      $this->renderPage($oldNewSessionData, $postStatus);
      return;

    } else if (isset($_POST['current-session-form-submit'])) {

      $currentSessionData = $_POST['current_session'];

      $status['current_session'] = self::updateSession($currentSessionData);

      $this->renderPage(null, $status);

    }
  }

  private static function createSession($data)
  {
    $session = $data['session'];

    try {
      if (AcademicSession::sessionExistsBySession($session)) {
        return [
          'posted' => false,

          'messages' => [
            $session . ' session already exists.'
          ]
        ];

      }

      if (AcademicSession::createSession($data)) {
        return [
          'posted' => true,

          'messages' => [$session . ' session successfully created.']
        ];
      }

    } catch (PDOException $e) {

      logPdoException($e, "Error occurred while creating new session.", self::logger());
    }

    return ['error' => true];
  }

  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('AcademicAdminSessionController');
  }

  public function renderPage($oldNewSessionData = null, $postStatus = null)
  {
    $theSession = AcademicAdminUtilities::getCurrentOrAlternativeSession();

    if ($theSession) {
      $currentSession = $theSession['session'];
      $alternative = $theSession['alternative'];
    }

    $link_template = __DIR__ . '/session-partial.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/session.min.js', true);

    $pageCssPath = path_to_link(__DIR__ . '/css/session.min.css', true);

    require(__DIR__ . '/../home/container.php');

  }

  private static function updateSession($data)
  {
    try {
      $session = AcademicSession::updateSession($data);

      if ($session) {
        return [
          'updated' => true
        ];
      }

    } catch (PDOException $e) {

      logPdoException($e, "Error while updating session", self::logger());
    }

    return [
      'updated' => false
    ];
  }

  /**
   * We first try to get the current session. if found, we tell caller that we did not
   * use the alternative algorithm. If we can't find current session , we use alternative
   * algorithm and then tell caller ['alternative' => true]. Caller can use this knowledge
   * to tell user.
   *
   * @return array|null
   */
  private function getCurrentSession()
  {
    try {
      $currentSession = AcademicSession::getCurrentSession();

      if ($currentSession) {
        return [
          'session' => $currentSession,
          'alternative' => false
        ];
      }

      $alternativeCurrentSession = AcademicSession::getAlternativeCurrentSession();

      if ($alternativeCurrentSession) {
        return [
          'session' => $alternativeCurrentSession,
          'alternative' => true
        ];
      }

    } catch (PDOException $e) {

      logPdoException($e, "Error while getting current session.", self::logger());
    }

    return null;
  }
}

$academic_session = new AcademicSessionController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $academic_session->renderPage();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $academic_session->post();
}
