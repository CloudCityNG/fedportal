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
    $logger = new SqlLogger(self::logger(), 'get all academic levels', $query);
    $stmt = get_db()->query($query);

    if ($stmt) {
      $logger->statementSuccess();
      $result = $stmt->fetchAll();

      if (count($result)) {
        $logger->dataRetrieved($result);
        return $result;
      }
    }

    $logger->noData();
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

  public static function getLevelsMapping()
  {
    $levels = [];

    foreach (self::getAllLevels() as $level) {
      $levels[$level['code']] = $level['description'];
    }

    return $levels;
  }
}
