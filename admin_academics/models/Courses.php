<?php

/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 18-Mar-15
 * Time: 6:46 PM
 */

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');

class Courses1
{

  private static $LOG_NAME = 'CoursesModel';

  public static function get_courses_for_semester_and_dept(array $data)
  {
    $query = "SELECT * FROM course_table
              WHERE department = :department
              AND semester = :semester";

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo(
      "About to get courses for a particular dept. in a particular semester with query: {$query} and params: ",
      $data
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($data)) {
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $log->addInfo("Statement executed successfully, courses are: ", $result);

      return $result;
    }

    $log->addWarning("Statement did not execute successfully.");

    return null;
  }

  /**
   * Given a student registration number, check if such student has signed up for courses
   * for the given semester
   * @param array $data - array []
   * @return bool
   */
  public static function student_signed_up_for_semester(array $data)
  {
    $query = "SELECT COUNT(*) FROM student_courses WHERE semester = :semester and reg_no = :reg_no";
    return true;
  }
}
