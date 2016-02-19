<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');

Class AcademicDepartment
{
  /**
   * Retrieve all the academic departments
   *
   * @return array|null - if data received successfully from database, return array of the form:
   * [
   *  ['id' => number|string, 'code' => string, 'description' => string],
   *  ['id' => number|string, 'code' => string, 'description' => string],
   *  .....
   * ]
   *
   * otherwise return null
   */
  public static function getAcademicDepartments()
  {
    $query = 'SELECT * FROM academic_departments';
    $logger = new SqlLogger(self::logger(), 'Get all departments', $query);
    $stmt = get_db()->query($query);

    if ($stmt) {
      $logger->statementSuccess();
      $returnedVal = $stmt->fetchAll();

      if (count($returnedVal)) {
        $logger->dataRetrieved($returnedVal);
        return $returnedVal;
      }
    }

    $logger->noData();
    return null;
  }

  private static function logger()
  {
    return get_logger('AcademicDepartmentModel');
  }

  /**
   * Given a department code e.g 'dental_therapy', get the department name e.g 'DENTAL THERAPY'
   *
   * @param string $code
   * @return null|string
   */
  public static function getDeptNameFromCode($code)
  {
    $query = 'SELECT description FROM academic_departments WHERE code = ?';
    $params = [$code];
    $logger = new SqlLogger(self::logger(), 'Get department name from its code', $query, $params);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $logger->statementSuccess();
      $returnedVal = $stmt->fetch(PDO::FETCH_NUM);

      if ($returnedVal) {
        $logger->dataRetrieved($returnedVal);
        return $returnedVal[0];
      }
    }

    $logger->noData();
    return null;
  }

  public static function getDepartmentCodeNameMap()
  {
    $departments = [];

    foreach (self::getAcademicDepartments() as $academicDepartment) {
      $departments[$academicDepartment['code']] = $academicDepartment['description'];
    }

    return $departments;
  }
}
