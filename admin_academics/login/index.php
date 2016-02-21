<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');
require_once(__DIR__ . '/../staff_profile/models/StaffProfile.php');

class AdminLogin
{

  private static $LOG_NAME = 'AdminLogin';

  public function get($adminLoginContext = null)
  {
    include(__DIR__ . '/view.php');
  }

  private static function getUserCapabilities($userName, $id)
  {
    $query = "SELECT code FROM staff_capability, staff_capability_assign
              WHERE staff_capability_id = staff_capability.id
              AND staff_profile_id=:staff_profile_id
              ";
    $params = ['username' => $userName, 'staff_profile_id' => $id];
    $logger = new SqlLogger(get_logger(self::$LOG_NAME), 'Get staff capabilities', $query, $params);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute(['staff_profile_id' => $id])) {
      $logger->statementSuccess();
      $result = $stmt->fetchAll();
      $logger->dataRetrieved($result);
      $returned = [];

      foreach ($result as $row) {
        $returned[$row['code']] = true;
      }

      return $returned;
    }

    $logger->noData();

    return null;
  }

  public function post()
  {

    $password = trim($_POST['password']);
    $username = trim($_POST['username']);

    $status = 'Login failed!';

    if (!$password || !$username) {
      $this->get([
        'status' => $status,
        'messages' => ['username or password incorrect']
      ]);

      return;
    }

    $result = StaffProfile::getStaff(['username' => $username]);

    if ($result) {
      $result = $result[0];
      $userDbPwd = $result['password'];

      if (!password_verify($password, $userDbPwd)) {
        $this->get([
          'status' => $status,
          'messages' => ['username or password incorrect']
        ]);
        return;
      }

      $isSuper = $result['is_super_user'];
      $capabilities = !$isSuper ? self::getUserCapabilities($username, $result['id']) : null;

      if (!$isSuper && !$capabilities && !isset($capabilities['can_view_admin_page'])) {
        $this->get([
          'status' => $status,
          'messages' => ['You are not authorized. Access denied!']
        ]);
        return;
      }

      unset($result['password']);

      if ($capabilities) {
        foreach ($capabilities as $key => $capability) {
          $result[$key] = $capability;
        }
      }

      if (session_status() === PHP_SESSION_NONE) session_start();

      session_regenerate_id();
      $_SESSION[USER_AUTH_SESSION_KEY] = json_encode($result, true);
      $_SESSION[STAFF_USER_SESSION_KEY] = STAFF_USER_SESSION_VALUE;
      $_SESSION[LAST_ACTIVITY_AUTH_PREFIX_KEY . STAFF_USER_SESSION_KEY] = time();
      session_write_close();

      header('location: ' . STATIC_ROOT . 'admin_academics/home/');

      $this->get([
        'status' => $status,
        'messages' => ['Unknown errors occurred!']
      ]);
    }

    $this->get([
      'status' => $status,
      'messages' => ['username or password incorrect']
    ]);
  }

}

$login = new AdminLogin;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $login->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login->post();
}
