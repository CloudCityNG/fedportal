<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

Class AcademicDepartment
{
  public static function getAcademicDepartments()
  {
    $query = 'SELECT * FROM academic_departments';

    self::logger()->addInfo("About to get all departments with query: {$query}");

    $stmt = get_db()->prepare($query);

    if ($stmt->execute()) {
      $returnedVal = $stmt->fetchAll();

      self::logger()->addInfo("Statement executed successfully, result is: ", $returnedVal);
      return $returnedVal;
    }

    self::logger()->addWarning("Statement did not execute successfully.");
    return null;
  }

  private static function logger()
  {
    return get_logger('AcademicDepartmentModel');
  }

  public static function getDeptNameFromCode($code)
  {
    $query = 'SELECT description FROM academic_departments WHERE code = ?';

    $params = [$code];

    self::logger()->addInfo("About to get department name from its code with query: {$query} and params: ", $params);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $returnedVal = $stmt->fetch(PDO::FETCH_NUM);

      if ($returnedVal) {
        $returnedVal = $returnedVal[0];

        self::logger()->addInfo("Statement executed successfully, result is: {$returnedVal}");
        return $returnedVal;
      }
    }

    self::logger()->addWarning("Statement did not execute successfully.");
    return null;
  }
}
