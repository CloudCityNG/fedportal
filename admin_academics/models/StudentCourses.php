<?php
/**
 * Created by maneptha on 16-Feb-15.
 */

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use Carbon\Carbon;

class StudentCourses
{

  private static $LOG_NAME = 'StudentCourses';

  public static function get_courses($reg, $semester)
  {

  }

  public static function bulk_create(array $data)
  {
    $query = "INSERT INTO student_courses(academic_year_code, reg_no, semester,
                                          course_id, level, created_at, updated_at)
              VALUES (:academic_year_code, :reg_no, :semester,
                      :course_id, :level, :created_at, :updated_at)";

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to create student courses with query: {$query} and params: ", $data);

    $db = get_db();

    $stmt = $db->prepare($query);

    $academic_year_code = '';
    $reg_no = '';
    $semester = '';
    $course_id = '';
    $level = '';

    $now = Carbon::now();
//    $created_at = $updated_at = $now->toDateTimeString();

    $stmt->bindParam('academic_year_code', $academic_year_code);
    $stmt->bindParam('reg_no', $reg_no);
    $stmt->bindParam('semester', $semester);
    $stmt->bindParam('course_id', $course_id);
    $stmt->bindParam('level', $level);
    $stmt->bindValue('created_at', $now->toDateTimeString());
    $stmt->bindValue('updated_at', $now->toDateTimeString());

    $returnedVal = [];

    foreach ($data as $course_array) {
      $academic_year_code = $course_array['academic_year_code'];
      $reg_no = $course_array['reg_no'];
      $semester = $course_array['semester'];
      $course_id = $course_array['course_id'];
      $level = $course_array['level'];

      $stmt->execute();

      $course_array['created_at'] = $now;
      $course_array['updated_at'] = $now;
      $course_array['deleted_at'] = null;

      $returnedVal[] = $course_array;
    }

    $log->addInfo("Student courses successfully created, the courses are: ", $returnedVal);

    return $returnedVal;
  }
}
