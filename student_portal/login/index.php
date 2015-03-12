<?php
include_once(__DIR__ . '/ConfirmPinController1.php');

include_once(__DIR__ . '/../../helpers/app_settings.php');


class LoginStudentController1
{
  private static $LOG_NAME = 'LoginStudentController';

  public function get()
  {

    include(__DIR__ . '/login_view.php');
    return;
  }

  public function post()
  {
    header("Content-Type: application/json");

    if (isset($_POST['auth']) && $_POST['auth']) {

      if ($this->authenticate()) {
        echo json_encode(['auth' => true]);

      } else {
        echo json_encode(['auth' => false]);
      }

    } else {

      $confirmPin = new ConfirmPinController1($_POST['confirm_pin']);

      $confirmed = $confirmPin->confirm() ? true : false;

      echo json_encode(['confirmed' => $confirmed]);
    }
  }

  private function authenticate()
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $reg_no = trim($_POST['regNo']);

    $password = trim($_POST['password']);

    $log->addInfo("about to login student with registration number $reg_no");

    if (!$reg_no || !$password) {

      $error_message = 'Invalid registration number or password';

      $log->addError("Login failed because: $error_message");

      return false;
    }

    $query = "SELECT COUNT(*) FROM pin_table WHERE number = ? AND pass = ?";

    try {
      $stmt = $db->prepare($query);

      $stmt->execute([$reg_no, $password]);

      if ($stmt->fetchColumn()) {

        $stmt->closeCursor();

        $log->addInfo("Login succeeds.");

        $this->set_session($reg_no);

        return true;

      } else {
        $log->addError(
          "Login fails. Login credentials supplied does not match those in database."
        );
      }

    } catch (Exception $e) {
      $log->addError("Login fails.");

      logPdoException($e, "Error while running query $query", $log);
    }

    return false;

  }

  private function set_session($reg_no)
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    session_regenerate_id();

    $_SESSION['REG_NO'] = $reg_no;

    session_write_close();
  }

}


$login = new LoginStudentController1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $login->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login->post();
}