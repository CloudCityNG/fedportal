<?php
require_once(__DIR__ . '/../helpers/app_settings.php');
require_once(__DIR__ . '/../helpers/databases.php');
require_once(__DIR__ . '/../helpers/SqlLogger.php');

/**
 * @param $regNo
 * @return array|null
 */
function getStudentProfile($regNo)
{
  $log = get_logger('GetStudentProfileForSessionSetting');
  $query = "SELECT * FROM freshman_profile WHERE personalno=:reg_no";
  $param = ['reg_no' => $regNo];
  $sqlLogger = new SqlLogger($log, 'Get student profile that will set in student session', $query, $param);
  $stmt = get_db()->prepare($query);

  if ($stmt->execute($param)) {
    $sqlLogger->statementSuccess();
    $result = $stmt->fetch();

    if (is_array($result) && count($result)) {
      $sqlLogger->dataRetrieved($result);
      $result['username'] = $result['personalno'];
      $result['last_name'] = isset($result['surname']) ? $result['surname'] : '';
      return $result;
    }
  }
  $sqlLogger->noData();
  return null;
}

function setStudentLoginSession($regNo)
{
  if (session_status() === PHP_SESSION_NONE) session_start();

  session_regenerate_id();
  unset($_SESSION[USER_AUTH_SESSION_KEY]);
  $student = getStudentProfile($regNo);
  $_SESSION[USER_AUTH_SESSION_KEY] = json_encode($student);
  $_SESSION[STUDENT_PORTAL_AUTH_KEY] = $regNo;
  $_SESSION['LAST-ACTIVITY-REG_NO'] = time();
  session_write_close();
  header('location: ' . STATIC_ROOT . 'student_portal/home1/');
}
