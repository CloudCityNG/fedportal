<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

Class AcademicDepartment
{
  private static $LOG_NAME = 'AcademicDepartmentModel';

  public static function get_academic_departments()
  {
    $query = 'SELECT * FROM academic_departments';

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to get all departments with query: {$query}");

    $stmt = get_db()->prepare($query);

    if ($stmt->execute()) {
      $returnedVal = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $log->addInfo("Statement executed successfully, result is: ", $returnedVal);
      return $returnedVal;
    }

    $log->addWarning("Statement did not execute successfully.");
    return null;
  }

  public static function get_dept_name_from_code($code)
  {
    $query = 'SELECT description FROM academic_departments WHERE code = ?';

    $params = [$code];

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to get department name from its code with query: {$query} and params: ", $params);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $returnedVal = $stmt->fetch(PDO::FETCH_NUM)[0];

      $log->addInfo("Statement executed successfully, result is: {$returnedVal}");
      return $returnedVal;
    }

    $log->addWarning("Statement did not execute successfully.");
    return null;
  }
}
