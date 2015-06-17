<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');


class AcademicLevels
{
  private static $LOG_NAME = 'AcademicLevelsModel';

  public static function getAllLevels()
  {
    $query = "SELECT * FROM academic_levels";

    $logMsg = SqlLogger::makeLogMessage('get all academic levels', $query);

    $stmt = get_db()->query($query);

    if ($stmt) {
      SqlLogger::logStatementSuccess(self::logger(), $logMsg);

      $result = $stmt->fetchAll();

      if (count($result)) {
        SqlLogger::logDataRetrieved(self::logger(), $logMsg, $result);
        return $result;
      }
    }

    SqlLogger::logNoData(self::logger(), $logMsg);
    return null;
  }

  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('AcademicLevelsModel');
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
