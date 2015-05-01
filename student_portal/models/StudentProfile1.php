<?php
require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

class StudentProfile1
{

  private static $LOG_NAME = 'StudentProfile';

  public static function get_student_by_reg($reg)
  {
    $query = "SELECT * FROM freshman_profile WHERE  personalno = ?";

    $param = [$reg];

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to get student profile with query: {$query} and params: ", $param);

    $db = get_db();

    $stmt = $db->prepare($query);

    if ($stmt->execute()) {
      $student = $stmt->fetch(PDO::FETCH_ASSOC);
      $log->addInfo("Statement ran successfully");
    }
  }
}
