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

      $postStatus = [
        'messages' => $valid['errors'],
        'post-form' => 'student-course-query',
        'posted' => false
      ];

      self::renderPage([
        'old_student_course_query_data' => $oldStudentCourseQueryData,
        'post_status' => $postStatus
      ]);

      return;
    }

    $regNo = $valid['reg-no'];

    //:TODO - handle exception
    $postedCourses = StudentCourses::getStudentCoursesForSemester(
      ['reg_no' => $regNo, 'semester_id' => $oldStudentCourseQueryData['semester_id']],
      true
    );

    if (!$postedCourses) {
      $postStatus = [
        'messages' => [
          'Student courses not found. May be student has not registered for courses for ' .
          $oldStudentCourseQueryData['semester']
        ],

        'post-form' => 'student-course-query',

        'posted' => false
      ];

      self::renderPage([
        'old_student_course_query_data' => $oldStudentCourseQueryData,
        'post_status' => $postStatus
      ]);
      return;
    }

    $student = $valid['student'];
    $profile = $student->getCompleteCurrentDetails();
    $profile['semester'] = $oldStudentCourseQueryData['semester'];

    self::renderPage([
      'student_courses_data' => ['courses' => $postedCourses, 'student' => $profile]
    ]);
  }

  /**
   * @param array $gradeCoursesContext
   */
  public static function renderPage(array $gradeCoursesContext = [])
  {
    $gradeCoursesContext['ten_most_recent_semesters'] = self::getSemestersForJSAutoComplete();
    $gradeCoursesContext['score_grade_mapping'] = StudentCourses::$SCORE_GRADE_MAPPING;
    $link_template = __DIR__ . '/grade-student-partial.php';
    $pageJsPath = path_to_link(__DIR__ . '/js/grade-student.min.js', true);
    $pageCssPath = path_to_link(__DIR__ . '/css/grade-student.min.css', true);
    require(__DIR__ . '/../../home/container.php');
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
     * :TODO - handle exceptions
     */
    $updatedCourses = StudentCourses::gradeStudent($postedCourses);
    $countUpdatedCourses = count($updatedCourses);

    /**
     * @var $studentCourses - student data that were sent back from client -
     * we extract only the courses
     */

    $postStatus = [
      'messages' => ["<strong>Success:</strong><br/>course(s) updated = {$countUpdatedCourses}."],

      'post-form' => 'student-course-score',

      'posted' => true
    ];

    self::renderPage(['just_graded_courses' => $_POST['student-score-table-text']]);
  }

  private static function logger()
  {
    return get_logger('AssessmentGradeStudentController');
  }
}
