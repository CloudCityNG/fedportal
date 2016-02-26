<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');
require_once(__DIR__ . '/../../helpers/models/Pin.php');
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');

class StudentDashboardLogin
{
  private static function logger()
  {
    return get_logger('StudentDashboardLogin');
  }

  public function get($studentLoginContext = null)
  {
    require(__DIR__ . '/view.php');
  }

  public function post()
  {

    $password = trim($_POST['password']);
    $username = trim($_POST['username']);
    $studentPortalLoginContext = ['username' => $username];

    if (!$password || !$username) {
      $this->get($studentPortalLoginContext);
      return;
    }

    try {
      $pinExists = Pin::get(['number' => $username, 'pass' => $password, '__exists' => true]);

      if ($pinExists) StudentProfile::setStudentLoginSession($username);
      else $this->get($studentPortalLoginContext);

    } catch (PDOException $e) {
      logPdoException($e, "Error while login in students", self::logger());
      $studentPortalLoginContext['message'] = 'Database error! Please try again. However if error persists please inform admin!';
      $this->get($studentPortalLoginContext);

    } catch (Exception $ex) {

      self::logger()->addError('Stack trace:', $ex->getTrace());
      $studentPortalLoginContext['message'] = 'General error! Please try again. However if error persists please inform admin!';
      $this->get($studentPortalLoginContext);
    }
  }
}

if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION[USER_AUTH_SESSION_KEY]);
$login = new StudentDashboardLogin;
if ($_SERVER['REQUEST_METHOD'] === 'GET') $login->get();
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') $login->post();
