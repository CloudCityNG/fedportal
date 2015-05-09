<?php

require_once(__DIR__ . '/../models/Semester.php');
require_once(__DIR__ . '/../models/AcademicSession.php');
require_once(__DIR__ . '/../models/StudentCourses.php');
require_once(__DIR__ . '/../../student_portal/models/StudentProfile1.php');

class AssessmentController
{
  public static function post()
  {
    if (isset($_POST['reg-no-submit'])) {
      self::sendStudentCoursesToClient();

    } else if (isset($_POST['student-course-score-form-submit'])) {
      self::updateCourseScores();
    }
  }

  private static function sendStudentCoursesToClient()
  {
    $oldStudentCourseQueryData = $_POST['student-course-query'];
    $valid = self::validatePost($oldStudentCourseQueryData);

    if (isset($valid['errors'])) {

      self::renderPage(
        $oldStudentCourseQueryData,

        [
          'errors' => $valid['errors'],
          'post-form' => 'student-course-query'
        ]
      );
      return;
    }


    $regNo = $valid['reg-no'];

    $postedCourses = StudentCourses::getStudentCurrentCourses(
      ['reg_no' => $regNo, 'semester_id' => $oldStudentCourseQueryData['semester_id']],
      true
    );

    if (!$postedCourses) {

      self::renderPage(
        $oldStudentCourseQueryData,

        [
          'errors' => [
            'Student courses not found. May be student has not registered for courses for ' .
            $oldStudentCourseQueryData['semester']
          ],

          'post-form' => 'student-course-query'
        ]
      );
      return;
    }

    $profile = (new StudentProfile($regNo))->getCompleteCurrentDetails();

    self::renderPage(
      null,

      null,

      [
        'courses' => $postedCourses,
        'student' => array_merge($profile, ['semester' => $oldStudentCourseQueryData['semester']])
      ]
    );
  }

  /**
   *
   * @param array $data - post data to be validated
   * @return array
   */
  private static function validatePost(array $data)
  {
    $errorMessages = [];

    if (!isset($data['reg-no'])) {
      $errorMessages[] = 'Student registration number can not be null.';
    }

    $regNo = trim($data['reg-no']);

    if (empty($regNo)) {
      $errorMessages[] = 'Student registration number can not be empty.';
    }

    $student = StudentProfile1::getStudentByRegNo($regNo);

    if (!$student) {
      $errorMessages[] = "Student with registration number '{$regNo}' does not exist";
      return [
        'errors' => $errorMessages,
      ];
    }

    return [
      'student' => $student,
      'reg-no' => $regNo
    ];
  }

  /**
   * @param array $oldStudentCourseQueryData
   * @param array|null $postStatus
   * @param array|null $studentCoursesData
   * @internal param array|null $courses
   * @internal param bool $postFailed
   * @internal param array $postStatus
   * @internal param array $oldCurrentSemesterData
   */
  public static function renderPage(
    array $oldStudentCourseQueryData = null,
    array $postStatus = null,
    array $studentCoursesData = null
  )
  {
    $tenMostRecentSemesters = self::getSemestersForJSAutoComplete();

    $scoreGradeMapping = StudentCourses::$SCORE_GRADE_MAPPING;

    $currentPage = [
      'title' => 'assessment',

      'link' => 'enter-grades'
    ];

    $link_template = __DIR__ . '/grade-student-form.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/assessment.min.js');

    $pageCssPath = path_to_link(__DIR__ . '/css/assessment.min.css');

    require(__DIR__ . '/../home/container.php');
  }

  /**
   * Jquery UI autocomplete plugin requires the source to be an object with keys 'label' and 'value'
   * @return array
   * @internal param int|string $howMany
   */
  private static function getSemestersForJSAutoComplete()
  {
    $semesters = [];

    try {
      $semesters = Semester::getSemesters(10);

      if ($semesters) {

        $semesters = array_map(function ($aSemester) {
          $aSemester['label'] = $aSemester['session']['session'] . ' - ' .
            Semester::renderSemesterNumber($aSemester['number']) . ' semester';

          $aSemester['value'] = $aSemester['id'];
          return $aSemester;
        }, $semesters);
      }

    } catch (PDOException $e) {

      logPdoException(
        $e, 'Error occurred while retrieving the two most recent academic sessions', self::logger());
    }

    return $semesters;
  }

  private static function logger()
  {
    return get_logger('AssessmentController');
  }

  private static function updateCourseScores()
  {
    $postedCourses = [];

    foreach ($_POST['student-courses'] as $id => $score) {
      $score = trim($score);

      if ($score) $postedCourses[$id] = $score;
    }

    $countCourses = count($postedCourses);

    /**
     * all data about student, including his courses that
     * we sent to the client. This is posted back from client
     * to server so we can re-render the details in case
     * of post success or failure
     *
     * :TODO is there a cleaner way than this round trip?
     */
    $studentCoursesData = json_decode($_POST['student-courses-data'], true);

    if (!$countCourses) {
      print_r($studentCoursesData);
      return;
    }

    /**
     * @var $updatedCourses - courses that we are
     * able to update with student's grades.
     * Note that it is possible that not all
     * scores sent to the database were updated.
     * :TODO examples of scenarios where this is possible?
     */
    $updatedCourses = StudentCourses::gradeStudent($postedCourses);
    $countUpdatedCourses = count($updatedCourses);

    /**
     * @var $studentCourses - student data that were sent back from client -
     * we extract only the courses
     */
    $studentCourses = $studentCoursesData['courses'];

    if ($countUpdatedCourses) {

      /**
       * @var array $successfullyUpdatedCourses - courses that were
       * successfully updated by database.
       * The code that performed the database update will
       * only send course id and score back to us.
       * We extract other course data from @var $studentCourses
       */
      $successfullyUpdatedCourses = [];

      foreach ($studentCourses as $studentCourse) {
        $courseId = $studentCourse['id'];
        if (isset($updatedCourses[$courseId])) {

          $successfullyUpdatedCourses[$courseId] = [
            'courseData' => $studentCourse,
            'score' => $updatedCourses[$courseId],
          ];
        }
      }

      print_r($successfullyUpdatedCourses);

    }
  }
}

$assessment = new AssessmentController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $assessment->renderPage();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $assessment->post();
}
