<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');

class Courses1
{

  private static $LOG_NAME = 'CoursesModel';

  /**
   * Get the courses that can be taken in a particular department in a particular semester, and optionally in a level
   *
   * @param array $data - an array that holds the department code and semester number (1 or 2), of the form:
   * [
   *  'department' => string, 'semester' => number|string, 'level' => string
   * ]
   * the 'level' key will only be given if we need to restrict returned courses to particular level
   *
   * @return array|null
   */
  public static function getCoursesForSemesterDeptLevel(array $data)
  {
    $query = "SELECT * FROM course_table
              WHERE department = :department
              AND semester = :semester";

    if (isset($data['level'])) $query .= ' AND class = :level';

    $logMessage = SqlLogger::makeLogMessage(
      'get courses for a particular department in a particular semester and optionally, level', $query, $data
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($data)) {
      SqlLogger::logStatementSuccess(self::logger(), $logMessage);

      $result = $stmt->fetchAll();

      if (count($result)) {
        SqlLogger::logDataRetrieved(self::logger(), $logMessage, $result);
        return $result;
      }
    }

    SqlLogger::logNoData(self::logger(), $logMessage);
    return null;
  }

  private static function logger()
  {
    return get_logger('CourseModel');
  }
}
