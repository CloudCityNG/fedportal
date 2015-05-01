<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');


class AcademicLevels
{
  private static $LOG_NAME = 'AcademicLevelsModel';

  public static function get_all_levels()
  {
    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT * FROM academic_levels";

    $log->addInfo("About to get all academic levels by executing the query : {$query}");

    $stmt = get_db()->query($query);

    if ($stmt) {
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $log->addInfo("Statement executed successfully, result is: ", $result);
      return $result;
    }

    $log->addWarning("Statement did not execute successfully");
    return null;
  }

  public static function get_level_by_code($code)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT * FROM academic_levels WHERE code LIKE ?";

    $params = [$code];

    $log->addInfo(
      "About to get levels using query: {$query} and params: ", $params
    );
  }
}
