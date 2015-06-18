<?php

require_once(__DIR__ . '/../../models/Semester.php');
require_once(__DIR__ . '/../../models/AcademicSession.php');
require_once(__DIR__ . '/../../models/StudentCourses.php');

class AssessmentGradeStudentController extends AssessmentController
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
    $valid = self::getStudentProfile($oldStudentCourseQueryData);

    if (isset($valid['errors'])) {

      self::renderPage(
        $oldStudentCourseQueryData,

        [
          'messages' => $valid['errors'],
          'post-form' => 'student-course-query',
          'posted' => false
        ]
      );
      return;
    }

    $regNo = $valid['reg-no'];

    $postedCourses = StudentCourses::getStudentCoursesForSemester(
      ['reg_no' => $regNo, 'semester_id' => $oldStudentCourseQueryData['semester_id']],
      true
    );

    if (!$postedCourses) {

      self::renderPage(
        $oldStudentCourseQueryData,

        [
          'messages' => [
            'Student courses not found. May be student has not registered for courses for ' .
            $oldStudentCourseQueryData['semester']
          ],

          'post-form' => 'student-course-query',

          'posted' => false
        ]
      );
      return;
    }

    $student = $valid['student'];
    $profile = $student->getCompleteCurrentDetails();
    $profile['semester'] = $oldStudentCourseQueryData['semester'];

    self::renderPage(
      null, null, ['courses' => $postedCourses, 'student' => $profile]
    );
  }

  /**
   * @param array $oldStudentCourseQueryData
   * @param array|null $postStatus
   * @param array|null $studentCoursesData
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

    $link_template = __DIR__ . '/grade-student-partial.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/grade-student.min.js', true);

    $pageCssPath = path_to_link(__DIR__ . '/css/grade-student.min.css', true);

    require(__DIR__ . '/../../home/container.php');
  }

  private static function logger()
  {
    return get_logger('AssessmentGradeStudentController');
  }

  /**
   * Take courses and scores set on client and update in the database. Tell user
   * how many courses were updated.
   */
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
      $successfullyUpdatedCourses = []; //:TODO will be sent back to client to indicate which courses were updated.

      foreach ($studentCourses as $studentCourse) {
        $courseId = $studentCourse['id'];
        if (isset($updatedCourses[$courseId])) {

          $successfullyUpdatedCourses[$courseId] = [
            'courseData' => $studentCourse,
            'score' => $updatedCourses[$courseId],
          ];
        }
      }

      self::renderPage(
        null,
        [
          'messages' => ["<strong>Success:</strong><br/>course(s) updated = {$countUpdatedCourses}."],

          'post-form' => 'student-course-score',

          'posted' => true
        ]
      );

    }
  }
}
