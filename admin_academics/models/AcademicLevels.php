<?php

/**
 * Created by maneptha on 12-Mar-15.
 */

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');


class AcademicLevels
{
  private static $LOG_NAME = 'Academic-Levels-Model';

  public static function get_all_levels()
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "SELECT * FROM academic_levels";

    $log->addInfo("About to get all academic levels by executing the query : {$query}");

    $stmt = $db->query($query);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

//print_r(AcademicLevels::get_all_levels());