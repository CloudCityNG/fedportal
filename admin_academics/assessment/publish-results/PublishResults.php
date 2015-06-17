<?php

require_once(__DIR__ . '/../../models/AcademicLevels.php');
require_once(__DIR__ . '/../../models/AcademicDepartment.php');
require_once(__DIR__ . '/../../models/Courses.php');
require_once(__DIR__ . '/../../models/StudentCourses.php');

class PublishResultsController extends AssessmentController
{
  public function post()
  {
    if (isset($_POST['semester-level-department-courses-submit'])) {
      $this->processSemesterLevelDeptForm();

    } else {
      $this->processPublishScoreForm();
    }
  }

  private function processSemesterLevelDeptForm()
  {
    $post = self::validateAndParseSemesterQueryForm();

    if (!$post['posted']) {
      $this->renderPage($post);
      return;
    }

    $sessionSemesterText = $post['session_semester_text'] . ' - ' . $post['level'] . ' - ' . $post['department_name'];

    $errors = false;
    $queryErrors = [
      'posted' => false,
      'messages' => ["No student has registered for courses for the given inputs: {$sessionSemesterText}!"]
    ];
    $courses = null;
    $courseIds = null;

    try {
      $courses = Courses1::getCoursesForSemesterDeptLevel([
        'department' => $post['department_code'],
        'level' => $post['level'],
        'semester' => $post['semester_number']
      ]);

      if ($courses) {
        $courseIds = array_map(function ($course) {
          return $course['id'];
        }, $courses);

        $courseIds = StudentCourses::courseIdsAndSemesterExist(
          ['course_ids' => $courseIds, 'semester_id' => $post['semester_id']]
        );
      }

    } catch (PDOException $e) {
      $errors = true;
      $queryErrors['messages'] = ['Database error! Courses cannot be fetched.'];
      logPdoException(
        $e,
        'Database error! Courses cannot be fetched.',
        self::logger()
      );

    } catch (Exception $e) {
      $errors = true;
      $queryErrors['messages'] = ['Unknown error!'];
      self::logGeneralError($e, self::logger());
    }

    if ($errors || !$courses || !$courseIds) {
      self::renderPage($queryErrors);
      return;
    }

    $coursesToClient = [];

    foreach ($courses as $course) {
      if (in_array($course['id'], $courseIds)) {
        $coursesToClient[] = $course;
      }
    }

    self::renderPage(null, null, [
      'courses' => $coursesToClient,
      'semester' => $sessionSemesterText
    ]);
  }

  /**
   * Validate HTTP post data for semester-level-department form
   *
   * @return array - if all data successfully validated, return an array of form:
   * [
   *     'semester_number' => numeric,
   *    'semester_id' => numeric,
   *    'department_code' => string,
   *    'department_name' => string,
   *    'level' => string,
   *    'session_semester_text' => string,
   *    'original_post_data' => [...]
   * ]
   */
  private static function validateAndParseSemesterQueryForm()
  {
    $query = $_POST['semester-course-query'];
    $errors = [];

    $returnedVal = ['posted' => true, 'original_post_data' => $query];

    $sessionSemesterText = $query['semester'];
    $sessionSemesterPattern = "=\d{4}/\d{4} - (\d)[stnd]{2} semester=";

    if (!preg_match($sessionSemesterPattern, $sessionSemesterText, $matches)) {
      $errors[] = 'Selected semester does not match pattern';

    } else {
      $returnedVal['semester_number'] = $matches[1];
    }

    $departmentCode = $query['department'];
    $departmentName = null;
    $invalidDeptText = 'Database error: can not validate selected department!';

    try {
      $departmentName = AcademicDepartment::getDeptNameFromCode($departmentCode);

    } catch (PDOException $e) {
      $errors[] = $invalidDeptText;
      logPdoException($e, $invalidDeptText, self::logger());

    } catch (Exception $e) {
      $errors = 'Unknown error: department name can not be retrieved';
      self::logGeneralError($e, self::logger(), 'department name can not be retrieved');
    }

    if (!$departmentName) $errors[] = $invalidDeptText;

    if (count($errors)) {
      $returnedVal['posted'] = false;
      $returnedVal['messages'] = $errors;

      return $returnedVal;
    }

    //TODO: the post keys below need to be validated
    $returnedVal['semester_id'] = $query['semester_id'];
    $returnedVal['level'] = $query['level'];
    $returnedVal['department_name'] = $departmentName;
    $returnedVal['department_code'] = $departmentCode;
    $returnedVal['session_semester_text'] = $sessionSemesterText;

    return $returnedVal;
  }

  private static function logger()
  {
    return get_logger('PublishResultsController');
  }

  public function renderPage(
    array $postStatus = null,
    array $oldSemesterCourseQueryData = null,
    array $coursesToClient = null
  )
  {
    $currentPage = [
      'title' => 'assessment',

      'link' => 'publish-results'
    ];

    $tenMostRecentSemesters = self::getSemestersForJSAutoComplete();

    $academicLevels = AcademicLevels::getAllLevels();

    $academicDepartments = AcademicDepartment::getAcademicDepartments();

    $link_template = __DIR__ . '/view.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/publish-results.min.js');

    $pageCssPath = path_to_link(__DIR__ . '/css/publish-results.min.css');

    require(__DIR__ . '/../../home/container.php');
  }

  private function processPublishScoreForm()
  {
    $coursesToPublishPost = null;

    if(isset($_POST['course_id'])) {
      $coursesToPublishPost = $_POST['course_id'];
    }
  }
}
