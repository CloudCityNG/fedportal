<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');

class Courses1
{

  private static $LOG_NAME = 'CoursesModel';

  /**
   * while the correct column name should have been level, we DDL with column name 'class'. some codes however use
   * the correct column name 'level'
   * @param array $params
   * @return array
   */
  private static function transformLevel(array $params)
  {
    if (isset($params['level'])) {
      $params['class'] = $params['level'];
      unset($params['level']);
    }

    return $params;
  }

  /**
   * Get the courses, filtering by the mapping $params
   *
   * @param array $params - an array that holds the filter criteria, of the form:
   * [
   *  'department' => string, 'semester' => number|string, 'level' => string
   * ]
   *
   * @return array|null
   */
  public static function getCourses(array $params = null)
  {
    $query = "SELECT * FROM course_table ";

    if ($params) {
      $params = self::transformLevel($params);

      $filter = getDbBindParamsFromColArray(array_keys($params));
      $query .= " WHERE {$filter}";

    } else {
      $params = [];
    }

    $logger = new SqlLogger(self::logger(), 'get courses with optional filter ', $query, $params);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
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


  public static function courseIsUnique(array $params)
  {
    $params = self::transformLevel($params);
    $filter = getDbBindParamsFromColArray(array_keys($params));
    $query = "SELECT COUNT(*) FROM course_table WHERE {$filter}";

    $logger = new SqlLogger(
      self::logger(),
      'check if the combination of code, department, level and semester for a course is unique ',
      $query,
      $params
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $logger->statementSuccess();
      $result = $stmt->fetchColumn();
      $logger->dataRetrieved([$result]);
      return $result;
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
