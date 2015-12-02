<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');
require_once(__DIR__ . '/../set_student_login_session.php');

class ConfirmPinController
{

  private static $LOG_NAME = 'ConfirmStudentRegistrationPinController';

  public function get() { include(__DIR__ . '/view.php'); }

  private static function setPostErrorSession(array $context)
  {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['ConfirmStudentRegistrationPinPost'] = json_encode($context);
    header('Location: ' . path_to_link(__DIR__));
  }

  public function post()
  {
    $post = $_POST['confirm_pin'];
    $password = trim($post['password']);
    $confirmPassword = trim($post['confirm-password']);
    $pin = trim($post['pin']);
    $regNo = trim($post['reg_no']);
    $email = trim($post['email']);
    $context = ['pin' => $pin, 'reg_no' => $regNo, 'email' => $email];

    if (!$password || !$confirmPassword || ($password !== $confirmPassword) || !$pin || !$regNo || !$email) {
      self::setPostErrorSession($context);
      return;
    }

    $query = "UPDATE pin_table SET number=:reg_no, pass=:password, email=:email WHERE number=:pin AND pass IS NULL";
    $queryParam = array_merge($context, ['password' => 'HIDDEN', 'confirm-password' => 'HIDDEN']);
    $log = get_logger(self::$LOG_NAME);
    $logger = new SqlLogger($log, 'Confirm student registration pin', $query, $queryParam);
    $stmt = get_db()->prepare($query);
    unset($queryParam['confirm-password']);
    $queryParam['password'] = $password;

    try {
      if ($stmt->execute($queryParam)) {
        $logger->statementSuccess();
        $result = $stmt->rowCount();
        $logger->dataRetrieved([$result]);

        if ($result) {
          setStudentLoginSession($regNo);
          return;
        }

        $logger->noData();
        self::setPostErrorSession($context);
      }
    } catch (PDOException $e) {
      logPdoException($e, "Unknown errors occurred while confirming pin '{$pin}' for student '{$regNo}'", $log);
      self::setPostErrorSession($context);
    }
  }

}

if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION[USER_AUTH_SESSION_KEY]);
$confirmPin = new ConfirmPinController;
if ($_SERVER['REQUEST_METHOD'] === 'GET') $confirmPin->get();
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') $confirmPin->post();
