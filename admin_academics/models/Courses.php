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
   * @param array $params - an array that holds the department code and semester number (1 or 2), of the form:
   * [
   *  'department' => string, 'semester' => number|string, 'level' => string
   * ]
   * the 'level' key will only be given if we need to restrict returned courses to particular level
   *
   * @return array|null
   */
  public static function getCoursesForSemesterDeptLevel(array $params)
  {
    $query = "SELECT * FROM course_table
              WHERE department = :department
              AND semester = :semester";

    if (isset($params['level'])) $query .= ' AND class = :level';

    $logMessage = SqlLogger::makeLogMessage(
      'get courses for a particular department in a particular semester and optionally, level', $query, $params
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
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


  /**
   * Get all courses
   *
   * @return array|null
   */
  public static function getAllCourses()
  {
    $query = "SELECT * FROM course_table";

    $logger = new SqlLogger(self::logger(), 'get all courses', $query);

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
   * @param array $params
   *
   * * @return array|null
   */
  public static function createCourse(array $params)
  {
    $query = "INSERT INTO course_table (title, code, department, class, semester, active, unit)
              VALUE (:title, :code, :department, :class, :semester, :active, :unit)";

    $logger = new SqlLogger(self::logger(), 'create course ', $query, $params);

    $db = get_db();
    $stmt = $db->prepare($query);

    if ($stmt->execute($params)) {
      $logger->statementSuccess();
      $params['id'] = $db->lastInsertId();
      $logger->dataRetrieved($params);
      return $params;
    }

    $logger->noData();
    return null;
  }

  private static function logger()
  {
    return get_logger('CourseModel');
  }
}
