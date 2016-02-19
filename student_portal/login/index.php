<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');
require_once(__DIR__ . '/../set_student_login_session.php');
require_once(__DIR__ . '/../../helpers/models/Pin.php');

class StudentDashboardLogin
{

  private static $LOG_NAME = 'StudentDashboardLogin';

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
    $adminLoginContext = ['username' => $username];

    if (!$password || !$username) {
      $this->get($adminLoginContext);
      return;
    }

    try {
      $pinExists = Pin::exists(['number' => $username, 'pass' => $password]);

      if ($pinExists) setStudentLoginSession($username);
      else $this->get($adminLoginContext);

    } catch (PDOException $e) {
      logPdoException($e, "Error while login in students", self::logger());
      $adminLoginContext['message'] = 'Database error! Please try again. However if error persists please inform admin!';
      $this->get($adminLoginContext);

    } catch (Exception $ex) {

      self::logger()->addError('Stack trace:', $ex->getTrace());
      $adminLoginContext['message'] = 'Database error! Please try again. However if error persists please inform admin!';
      $this->get($adminLoginContext);
    }
  }
}

if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION[USER_AUTH_SESSION_KEY]);
$login = new StudentDashboardLogin;
if ($_SERVER['REQUEST_METHOD'] === 'GET') $login->get();
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') $login->post();
