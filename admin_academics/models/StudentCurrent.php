<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

class StudentCurrent
{
  private static $LOG_NAME = 'StudentCurrentModel';

  public static function create(array $data)
  {
    $query = "INSERT INTO student_currents(reg_no, academic_year, level, dept_code, dept_name)
              VALUES (:reg_no, :academic_year, :level, :dept_code, :dept_name)";

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to insert student current academic parameters with query {$query} and params: ", $data);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($data)) {
      $log->addInfo("Current academic parameters successfully inserted.");

    } else {
      $log->addWarning("Statement did not execute correctly.");
    }
  }
}
