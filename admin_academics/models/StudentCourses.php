<?php

require_once(__DIR__ . '/../../helpers/databases.php');
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/Semester.php');

use Carbon\Carbon;

class StudentCourses
{
  /**
   * @var array - [low, high, letter grade, point] a score between high and low
   * inclusive will be awarded the grade letter and number point
   */
  public static $SCORE_GRADE_MAPPING = [
    [80, 100, 'A', 4.0],

    [70, 79, 'AB', 3.5],

    [60, 69, 'B', 3.0],

    [50, 59, 'BC', 2.5],

    [45, 49, 'C', 2.0],

    [0, 44, 'F', 0.0]
  ];

  /**
   * Get the courses that a student has registered for a particular semester
   *
   * @param array $data - in the form ['reg_no' => string, 'semester_id' => string|int]
   * @param bool $withLetterGrades - see @method "getStudentCourses"
   * @param bool $gradeNullScore - see @method "getStudentCourses"
   * @return array|null
   */
  public static function getStudentCoursesForSemester(array $data, $withLetterGrades = false, $gradeNullScore = false)
  {
    return self::getStudentCourses($data, $withLetterGrades, $gradeNullScore);
  }

  /**
   * Get the courses that a student has registered for
   * and optionally restrict to particular semester
   *
   * @param array $data - in the form ['reg_no' => string]  or
   * ['reg_no' => string, 'semester_id' => string|int] if we wish
   * to restrict to particular semester
   *
   * @param bool $withLetterGrades - whether we should computer letter grade for each score
   * @param bool $gradeNullScore - whether to grade null scores. By default, we will not
   * assign letter grades to null scores. We only do so if caller of this method
   * specifically asks for it.
   *
   * @return array|null
   */
  public static function getStudentCourses(array $data, $withLetterGrades = false, $gradeNullScore = false)
  {
    $query = "SELECT student_courses.id AS `id`, `reg_no`, `level`, `semester_id`, `score`, `title`, `unit`,
              `department`, `code`

              FROM student_courses JOIN course_table ON (course_id = course_table.id)
              WHERE reg_no = :reg_no";

    if (isset($data['semester_id'])) $query .= ' AND semester_id = :semester_id';
    if (isset($data['publish'])) $query .= ' AND publish = :publish';

    $logger = new SqlLogger(self::logger(), 'get student courses', $query);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($data)) {
      $logger->statementSuccess();

      $result = $stmt->fetchAll();

      if (count($result)) {
        if ($withLetterGrades) $result = self::addLetterGrades($result, $gradeNullScore);

        $logger->dataRetrieved($result);

        return $result;
      }
    }

    $logger->noData();
    return null;
  }

  private static function logger()
  {
    return get_logger('StudentCoursesModel');
  }

  /**
   * Takes student courses retrieved from database and adds a key for
   * grade with corresponding letter grade for those courses that have
   * valid scores
   *
   * @param array $data - student courses retrieved from database with their
   * associated scores
   *
   * @param bool $gradeNullScore - whether to grade null scores. By default, we will not
   * assign letter grades to null scores. We only do so if caller of this method
   *
   * @return array - we return the @argument $data augmented with letter grades
   * corresponding to the scores.
   */
  private static function addLetterGrades(array $data, $gradeNullScore = false)
  {
    $returnedVal = [];

    foreach ($data as $row) {
      $grade = null;
      $point = null;

      if (isset($row['score'])) {
        $score = $row['score'];

        if ($score) {
          $score = floatval($score);

          foreach (self::$SCORE_GRADE_MAPPING as $scoreGrade) {
            $min = $scoreGrade[0];
            $max = $scoreGrade[1];

            if ($min <= $score && $score <= $max) {
              $grade = $scoreGrade[2];
              $point = $scoreGrade[3];
              break;
            }
          }

        }
      }

      if ($gradeNullScore && !$grade) {
        $grade = 'F';
        $point = 0;
        $row['score'] = '0.00';
      }

      $row['grade'] = $grade;
      $row['point'] = $point;
      $returnedVal[] = $row;
    }

    return $returnedVal;

  }

  /**
   * Get all the semester IDs in which a student signed up for courses
   *
   * @param string $regNo - student registration/matriculation number
   * @return array|null
   */
  public static function getSemesters($regNo)
  {
    $query = "SELECT DISTINCT(semester_id) FROM student_courses WHERE reg_no = ?";
    $param = [$regNo];

    $logger = new SqlLogger(
      self::logger(), 'get all the semester IDs in which a student signed up for courses', $query, $param);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $logger->statementSuccess();

      $result = [];

      while ($row = $stmt->fetch()) {
        $result[] = $row['semester_id'];
      }

      if (count($result)) {
        $logger->dataRetrieved($result);
        return $result;
      }
    }

    $logger->noData();
    return null;
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

    $logger = new SqlLogger(
      self::logger(),
      'create several courses for one student in one semester',
      $query,
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
      $logger->statementSuccess([$courseId]);

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

    $logger->dataRetrieved($returnedVal);
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

    $logger = new SqlLogger(
      self::logger(), 'find out if student has signed up courses for given semester', $query, $data);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($data)) {
      $logger->statementSuccess();

      $result = $stmt->fetchColumn();

      $logger->dataRetrieved([$result]);
      return $result;
    }

    $logger->noData();
    return null;
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

    $logger = new SqlLogger(self::logger(), 'grade student', $data);

    $stmt = get_db()->prepare($query);

    $id = '';
    $score = '';

    $stmt->bindParam('id', $id);
    $stmt->bindParam('score', $score);

    $returnedVal = [];

    foreach ($data as $id => $score) {
      if ($stmt->execute()) {
        $logger->statementSuccess([$id, $score]);
        $returnedVal[$id] = $score;
      }
    }

    $countData = count($data);
    $countReturnedVal = count($returnedVal);

    if ($countReturnedVal === $countData) {
      $logger->dataRetrieved($returnedVal);
      return $returnedVal;
    }

    $logger->noData();
    return null;
  }

  /**
   * Given an array of course IDs, query the database for student courses that match the course IDs in
   * the given semester ID
   *
   * @param array $data - of the form:
   * [
   *  'course_ids' => [numeric, numeric, numeric],
   *  'semester_id' => numeric
   * ]
   * @return array|null
   */
  public static function courseIdsAndSemesterExist(array $data)
  {
    $courseIdsDbArray = toDbArray($data['course_ids']);

    $query = "select DISTINCT course_id, publish from student_courses
              WHERE course_id IN {$courseIdsDbArray}
              AND semester_id = ?";

    $param = [$data['semester_id']];

    $logger = new SqlLogger(
      self::logger(),
      'get courses whose IDs are in array of supplied course IDs and semester ID matches supplied semester ID',
      $query,
      $param
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $logger->statementSuccess();

      $returnedVal = [];

      while ($row = $stmt->fetch()) $returnedVal[$row['course_id']] = $row['publish'];

      if (count($returnedVal)) {
        $logger->dataRetrieved($returnedVal);
        return $returnedVal;
      }
    }

    $logger->noData();
    return null;
  }

  /**
   * Either publish an un-published score or un-publish a published score
   *
   * @param array $courses - array of course IDs to be published/un-published, of form:
   * [
   *    'course_id' => publish(number), ...
   * ]
   * The value is either 0 for un-publish or 1 for publish
   *
   * @param string|number $semesterId
   * @return array - of courses IDs that are successfully updated
   */
  public static function publishScores(array $courses, $semesterId)
  {
    $query = "UPDATE student_courses SET publish = :publish WHERE course_id = :course_id AND semester_id = :semester_id";

    $logger = new SqlLogger(self::logger(), 'publish or un-publish student scores', $query, $courses);
    $publish = $courseId = null;

    $stmt = get_db()->prepare($query);
    $stmt->bindValue('semester_id', $semesterId);
    $stmt->bindParam('course_id', $courseId);
    $stmt->bindParam('publish', $publish);

    $updated = [];

    foreach ($courses as $courseId => $publish) {
      if ($stmt->execute()) {
        $logger->statementSuccess([$courseId => $publish]);
        $updated[] = $courseId;
      }
    }

    return $updated;
  }
}
