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
   * @param array $data - in the form ['reg_no' => string, 'semester_id' => string|int]
   * @return array|null
   */
  public static function getStudentCurrentCourses(array $data)
  {
    $query = "SELECT student_courses.id as `id`, `reg_no`, `level`, `semester_id`, `score`, `title`, `unit`,
              `department`, `code`

              FROM student_courses JOIN course_table ON (course_id = course_table.id)
              WHERE reg_no = ?
              AND semester_id = ?";

    $params = [$data['reg_no'], $data['semester_id']];

    self::logger()->addInfo("About to get student courses with query: {$query} and params: ", $params);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $result = $stmt->fetchAll();

      if (count($result)) {
        self::logger()->addInfo("Statement executed successfully. Current courses are: ", $result);
        return $result;
      }
    }

    self::logger()->addWarning("Current courses for student can not be retrieved.");
    return null;
  }

  private static function logger()
  {
    return get_logger('StudentCoursesModel');
  }

  /**
   * Given a student, her level and semester details, create several courses for her
   *
   * @param array $courseIds
   * @param array $studentDetails
   * @return array
   */
  public static function bulkCreateForStudentForSemester(array $courseIds, array $studentDetails)
  {
    $semester = Semester::getSemesterByNumberAndSession(
      $studentDetails['semester'],
      $studentDetails['academic_year_code']
    );

    $query = "INSERT INTO student_courses(reg_no, semester_id,
                                          course_id, level, created_at, updated_at)
              VALUES (:reg_no, :semester_id,
                      :course_id, :level, :created_at, :updated_at)";

    self::logger()->addInfo(
      "About to create several courses for one student in one semester with query: {$query} and params: ",
      [$courseIds, $studentDetails]
    );

    $stmt = get_db()->prepare($query);

    $now = Carbon::now();

    $stmt->bindValue('reg_no', $studentDetails['reg_no']);
    $stmt->bindValue('semester_id', $semester['id']);
    $stmt->bindValue('level', $studentDetails['level']);
    $stmt->bindValue('created_at', $now->toDateTimeString());
    $stmt->bindValue('updated_at', $now->toDateTimeString());

    $courseId = '';
    $stmt->bindParam('course_id', $courseId);

    $returnedVal = [];

    foreach ($courseIds as $courseId) {
      $stmt->execute();

      $returnedVal[] = array_merge(
        $studentDetails,
        [
          'created_at' => $now,
          'updated_at' => $now,
          'deleted_at' => null,
          'course_id' => $courseId
        ]
      );
    }

    self::logger()->addInfo("Student courses successfully created, the courses are: ", $returnedVal);
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

    self::logger()->addInfo(
      "About to find out if student has signed up courses for given semester with query: {$query} and params: ",
      $data
    );

    $stmt = get_db()->prepare($query);
    $stmt->execute($data);

    $result = $stmt->fetchColumn();
    self::logger()->addInfo("Result is: {$result}");

    return $result;
  }

  /**
   * Given student course ids and corresponding scores, update the `student_courses` table with the scores
   *
   * @param array $data - array of the form ['student_course_id' => score]
   * @return array|null - return an array of courses successfully updated. Return null if no course was updated
   */
  public static function gradeStudent(array $data)
  {
    $query = "UPDATE student_courses SET score = :score WHERE id = :id";

    self::logger()->addInfo("About to grade student by executing query: {$query}, and params: ", $data);

    $stmt = get_db()->prepare($query);

    $id = '';
    $score = '';

    $stmt->bindParam('id', $id);
    $stmt->bindParam('score', $score);

    $returnedVal = [];

    foreach ($data as $id => $score) {
      if ($stmt->execute()) {
        $returnedVal[$id] = $score;
      }
    }

    $countData = count($data);
    $countReturnedVal = count($returnedVal);

    if ($countReturnedVal === $countData) {
      self::logger()->addInfo('All scores successfully updated for [student_course_id => score] ', $returnedVal);
      return $returnedVal;
    }

    self::logger()->addWarning(
      "Unable to update some all or scores for student courses. {$countData} courses given but only {$countReturnedVal} updated"
    );
    return null;
  }
}
