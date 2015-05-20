<?php
//require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../helpers/app_settings.php');
require_once(__DIR__ . '/models/AcademicSession.php');

class AcademicAdminUtilities
{
  /**
   * We first try to get the current session. if found, we tell caller that we did not
   * use the alternative algorithm. If we can't find current session , we use alternative
   * algorithm and then tell caller ['alternative' => true]. Caller can use this knowledge
   * to tell user.
   *
   * @return array|null
   */
  public static function getCurrentOrAlternativeSession()
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

  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('AcademicAdminUtilities');
  }
}
