<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');
require_once(__DIR__ . '/../set_student_login_session.php');

class StudentDashboardLogin
{

  private static $LOG_NAME = 'StudentDashboardLogin';

  public function get($studentLoginContext = null) { include(__DIR__ . '/view.php'); }

  public function post()
  {

    $password = trim($_POST['password']);
    $username = trim($_POST['username']);
    $adminLoginContext = ['username' => $username];

    if (!$password || !$username) {
      $this->get($adminLoginContext);
      return;
    }

    $query = "SELECT COUNT(*) FROM pin_table WHERE number=:username AND pass=:password";
    $queryParam = ['username' => $username, 'password' => 'HIDDEN'];
    $logger = new SqlLogger(get_logger(self::$LOG_NAME), 'Login student', $query, $queryParam);
    $queryParam['password'] = $password;
    $stmt = get_db()->prepare($query);

    try {
      if ($stmt->execute($queryParam)) {
        $logger->statementSuccess();
        $result = $stmt->fetchColumn();
        $logger->dataRetrieved([$result]);

        if ($result) {
          setStudentLoginSession($username);
          return;
        }

        $logger->noData();
        $this->get($adminLoginContext);
        return;
      }
    } catch (PDOException $e) {
      $log = get_logger(self::$LOG_NAME);
      $log->addError("Login fails.");
      logPdoException($e, "Error while running login students with query {$query}", $log);
      $adminLoginContext['message'] = 'Unknown error occurred! Please try again.';
      $this->get($adminLoginContext);
    }
  }
}

if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION[USER_AUTH_SESSION_KEY]);
$login = new StudentDashboardLogin;
if ($_SERVER['REQUEST_METHOD'] === 'GET') $login->get();
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') $login->post();
