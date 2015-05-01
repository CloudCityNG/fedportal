<?php

require_once(__DIR__ . '/../../helpers/databases.php');

require_once(__DIR__ . '/../../helpers/app_settings.php');

class AdminFinanceLogin
{

  private static $LOG_NAME = 'AdminFinanceLogin';

  public function get()
  {
    include(__DIR__ . '/view.php');
  }

  public function post()
  {
    header("Content-Type: application/json");

    $password = trim($_POST['password']);

    if ($password == false) {
      echo json_encode(['auth' => false]);

      return;
    }

    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT COUNT(*) FROM admin_fin_table WHERE pass=?";

    $log->addInfo("About to do login with query: $query");

    $stmt = $db->prepare($query);

    $stmt->execute([$password]);

    if ($stmt->fetchColumn()) {

      if (session_status() === PHP_SESSION_NONE) {
        session_start();

        session_regenerate_id();
        $_SESSION['FINANCE'] = "FINANCE-Administrator";
        session_write_close();
      }

      $log->addInfo("Login successful. Will redirect to dashboard");

      echo json_encode(['auth' => true]);

      return;
    }

    $log->addError("Login unsuccessful");

    echo json_encode(['auth' => false]);
  }

}

$login = new AdminFinanceLogin;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $login->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login->post();
}
