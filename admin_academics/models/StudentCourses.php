<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/Semester.php');

use Carbon\Carbon;

class StudentCourses
{

  private static $LOG_NAME = 'StudentCoursesModel';

  /**
   * Get the courses that a student has registered for a particular semester
   *
   * @param array $data
   * @return array|null
   */
  public static function get_student_current_courses(array $data)
  {
    $semester = Semester::getSemesterByNumberAndSession($data['semester'], $data['session']);

    $query = "SELECT title, code, unit
              FROM student_courses JOIN course_table ON (course_id = course_table.id)
              WHERE reg_no = ?
              AND semester_id = ?";

    $params = [$data['reg_no'], $semester['id']];

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to get student courses with query: {$query} and params: ", $params);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $log->addInfo("Statement executed successfully. Current courses are: ", $result);
      return $result;
    }

    $log->addWarning("Statement did not execute successfully");
    return null;
  }

  /**
   * Given a student, her level and semester details, create several courses for her
   *
   * @param array $course_ids
   * @param array $student_details
   * @return array
   */
  public static function bulkCreateForStudentForSemester(array $course_ids, array $student_details)
  {
    $semester = Semester::getSemesterByNumberAndSession(
      $student_details['semester'],
      $student_details['academic_year_code']
    );

    $query = "INSERT INTO student_courses(reg_no, semester_id,
                                          course_id, level, created_at, updated_at)
              VALUES (:reg_no, :semester_id,
                      :course_id, :level, :created_at, :updated_at)";

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo(
      "About to create several courses for one student in one semester with query: {$query} and params: ",
      [$course_ids, $student_details]
    );

    $stmt = get_db()->prepare($query);

    $now = Carbon::now();

    $stmt->bindValue('reg_no', $student_details['reg_no']);
    $stmt->bindValue('semester_id', $semester['id']);
    $stmt->bindValue('level', $student_details['level']);
    $stmt->bindValue('created_at', $now->toDateTimeString());
    $stmt->bindValue('updated_at', $now->toDateTimeString());

    $course_id = '';
    $stmt->bindParam('course_id', $course_id);

    $returnedVal = [];

    foreach ($course_ids as $course_id) {
      $stmt->execute();

      $returnedVal[] = array_merge(
        $student_details,
        [
          'created_at' => $now,
          'updated_at' => $now,
          'deleted_at' => null,
          'course_id' => $course_id
        ]
      );
    }

    $log->addInfo("Student courses successfully created, the courses are: ", $returnedVal);
    return $returnedVal;
  }

  /**
   * Given a student registration number, check if such student has signed up for courses
   * for the given semester
   * @param array $data - array ['reg_no' =>, 'semester_id' =>]
   * @return bool
   */
  public static function student_signed_up_for_semester(array $data)
  {
    $query = "SELECT COUNT(*) FROM student_courses
              WHERE semester_id = :semester_id AND reg_no = :reg_no";

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo(
      "About to find out if student has signed up courses for given semester with query: {$query} and params: ",
      $data
    );

    $stmt = get_db()->prepare($query);
    $stmt->execute($data);

    $result = $stmt->fetchColumn();
    $log->addInfo("Result is: {$result}");

    return $result;
  }
}
