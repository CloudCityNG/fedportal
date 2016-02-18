<?php
require_once(__DIR__ . '/../../models/AcademicDepartment.php');
require_once(__DIR__ . '/../../models/AcademicLevels.php');
require_once(__DIR__ . '/../../models/Courses.php');

class CreateCourseController
{
  static $createdOrModifiedKey = 'created_or_modified_course';

  private static function logger()
  {
    return get_logger('CreateCourseController');
  }

  private static function testUnique(array $course, array $exclude = null)
  {
    $status = ['valid' => false];

    try {
      $isUnique = Courses1::courseIsUnique([
        'class' => $course['class'],
        'department' => $course['department'],
        'semester' => $course['semester'],
        'code' => $course['code'],
      ], $exclude);

      if ($isUnique) {
        $status['messages'] = ['A course already exists for the combination of level, department, semester and code!'];
        return $status;
      }

    } catch (PDOException $e) {
      logPdoException(
        $e, 'Database error occurred while checking course uniqueness of: ' . print_r($course, true), self::logger()
      );

      $status['messages'] = ['A database error occurred! Please try again or if it persists, please alert admin.'];
      return $status;

    } catch (Exception $e) {

      $status['messages'] = ['An unknown error occurred! Please try again or if it persists, please alert admin.'];
      return $status;

    }

    return ['valid' => true];
  }

  private static function confirmPost(array $course)
  {
    $messages = [];

    if (!$course['class']) $messages[] = 'Level can not be empty';
    if (!$course['department']) $messages[] = 'Department can not be empty';
    if (!$course['semester']) $messages[] = 'Semester can not be empty';
    if (!$course['code']) $messages[] = 'Course code can not be empty';
    if (!$course['title']) $messages[] = 'Course title can not be empty';
    if (!$course['unit']) $messages[] = 'Course unit can not be empty';

    if (count($messages)) return ['valid' => false, 'messages' => $messages];

    $uniqueness = self::testUnique($course);
    if (!$uniqueness['valid']) return $uniqueness;

    return ['valid' => true];
  }

  /**
   * @param array $createCourseContext
   */
  public function renderPage(array $createCourseContext = [])
  {
    $createCourseContext['department_mapping'] = AcademicDepartment::getDepartmentCodeNameMap();
    $createCourseContext['levels_mapping'] = AcademicLevels::getLevelsMapping();

    if (isset($createCourseContext['query'])) {
      $courseId = getIdFromQuery($createCourseContext['query'], 'course_id');

      if ($courseId) {
        $course = Courses1::getCourses(['id' => $courseId]);

        if ($course) {
          $createCourseContext['edit'] = true;
          $createCourseContext['displayed_course'] = $course[0];
        }
      }
    }

    $link_template = __DIR__ . '/view.php';
    $pageJsPath = path_to_link(__DIR__ . '/js/create-course.min.js', true);
    $pageCssPath = path_to_link(__DIR__ . '/css/create-course.min.css', true);

    require(__DIR__ . '/../../home/container.php');
  }

  private function edit()
  {
    $post = isset($_POST['course']) ? $_POST['course'] : [];
    $unmodifiedCourse = json_decode($_POST['current_course_data'], true);
    list($modified, $uniqueness) = self::getModifiedFields($unmodifiedCourse, $post);
    $currentCourseModified = array_merge($unmodifiedCourse, $modified);
    $context = [
      'unmodified_course' => $unmodifiedCourse,
      'displayed_course' => $currentCourseModified,
      'edit' => true
    ];
    $idFilter = ['id' => $unmodifiedCourse['id']];

    if (count($uniqueness)) {
      $isUnique = self::testUnique($uniqueness, $idFilter);

      if (!$isUnique['valid']) {
        $context['messages'] = $isUnique['messages'];
        self::setPostSession(false, 'Course modification failed!', $context);
        $this->renderPage($context);
        return;
      }
    }

    try {
      Courses1::updateCourse($modified, $idFilter);

      self::setPostSession(
        true, 'Course successfully modified!', [self::$createdOrModifiedKey => $currentCourseModified], true
      );

    } catch (PDOException $e) {
      $this->handleException($e, $unmodifiedCourse, $context, 'Course modification failed');

    } catch (Exception $e) {
      $this->handleException($e, $unmodifiedCourse, $context, 'Course modification failed');
    }
  }

  private function handleException(Exception $e, array $courseData, array $context, $message)
  {
    $logMessage = "{$message} with post data: " . print_r($courseData, true);

    if ($e instanceof PDOException) {
      $context['messages'] = ['A database error occurred! Please try again or if it persists, please alert admin.'];
      logPdoException($e, $logMessage, self::logger());
    } else {
      $context['messages'] = ['General error occurred! Please try again or if it persists, please alert admin.'];
      self::logger()->addError($logMessage, ['original_error_message' => $e->getMessage()]);
    }

    self::setPostSession(false, $message, $context);
    $this->renderPage($context);

    return;
  }

  /**
   * If we are carrying an out an edit, we need to isolate fields that have been modified and if any of the modified
   * fields is one of the fields making up the uniqueness constraint, we need to know this
   *
   * @param array $currentCourse
   * @param array $modifiedCourse
   * @return array - an array of the form [mapping, mapping]. 1st mapping is the modified fields and their values, while
   * second mapping is the keys and values for columns that are part of the uniqueness constraint i.e one of
   * 'code', 'department', 'class', 'semester'
   */
  private static function getModifiedFields(array $currentCourse, array $modifiedCourse)
  {
    $result = [];
    $uniquenessCheck = [];
    $uniquenessKeys = ['code', 'department', 'class', 'semester'];

    foreach ($currentCourse as $oldKey => $oldVal) {

      if (isset($modifiedCourse[$oldKey])) {
        $val = trim($modifiedCourse[$oldKey]);

        if (in_array($oldKey, ['code', 'title'])) $val = strtoupper($val);

        $result[$oldKey] = $val;

        // if any of 'code', 'department', 'class', 'semester' has changed, we note this change
        if (in_array($oldKey, $uniquenessKeys)) $uniquenessCheck[$oldKey] = $val;
      }
    }

    //if any of 'code', 'department', 'class', 'semester' has been modified, make sure to copy the other unmodified
    //into uniqueness check cos all 4 are required for the check
    if (count($uniquenessCheck)) {
      foreach ($uniquenessKeys as $uniquenessKey) {
        if (!isset($result[$uniquenessKey])) $uniquenessCheck[$uniquenessKey] = $currentCourse[$uniquenessKey];
      }
    }

    return [$result, $uniquenessCheck];
  }

  private static function cleanNewCoursePost($course)
  {
    $post = [];

    foreach (['title', 'code', 'department', 'class', 'semester', 'active', 'unit',] as $item) {
      $val = trim($course[$item]);

      if (in_array($item, ['code', 'title'])) $val = strtoupper($val);

      $post[$item] = $val;
    }

    return $post;
  }

  public function post()
  {
    if (isset($_POST['current_course_data'])) {
      $this->edit();
      return;
    }

    $course = $_POST['course'];
    $post = self::cleanNewCoursePost($course);

    $context = ['displayed_course' => $post,];

    $valid = self::confirmPost($post);

    if (!$valid['valid']) {
      $context['messages'] = $valid['messages'];
      self::setPostSession(false, 'Course creation failed!', $context);
      $this->renderPage($context);
      return;
    }

    try {
      Courses1::createCourse($post);

      self::setPostSession(true, 'Course successfully created!', [self::$createdOrModifiedKey => $post], true);

    } catch (PDOException $e) {
      logPdoException($e, 'Course creation failed with post data: ' . print_r($post, true), self::logger());

      $context['messages'] = ['A database error occurred! Please try again or if it persists, please alert admin.'];
      self::setPostSession(false, 'Course creation failed!', $context);
      $this->renderPage($context);
    }

  }

  private static function setPostSession($posted, $status, array $context, $redirect = false)
  {
    $_SESSION['CREATE-COURSE-POST-KEY'] = json_encode(
      array_merge(['posted' => $posted, 'status' => $status], $context)
    );

    //we should redirect on POST success
    if ($redirect) header("Location: " . path_to_link(__DIR__ . '/..') . '?create-course');
  }
}
