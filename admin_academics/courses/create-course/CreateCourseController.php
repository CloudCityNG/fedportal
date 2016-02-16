<?php
require_once(__DIR__ . '/../../models/AcademicDepartment.php');
require_once(__DIR__ . '/../../models/AcademicLevels.php');
require_once(__DIR__ . '/../../models/Courses.php');

class CreateCourseController
{
  private static function logger()
  {
    return get_logger('CreateCourseController');
  }

  private static function courseExists($course)
  {
    $status = ['valid' => false];

    try {
      $isUnique = Courses1::courseIsUnique([
        'class' => $course['class'],
        'department' => $course['department'],
        'semester' => $course['semester'],
        'code' => $course['code'],
      ]);

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

  public function renderPage(array $createCourseContext = [])
  {
    $createCourseContext['department_mapping'] = AcademicDepartment::getDepartmentCodeNameMap();
    $createCourseContext['levels_mapping'] = AcademicLevels::getLevelsMapping();

    $link_template = __DIR__ . '/view.php';
    $pageJsPath = path_to_link(__DIR__ . '/js/create-course.min.js', true);
    $pageCssPath = path_to_link(__DIR__ . '/css/create-course.min.css', true);

    require(__DIR__ . '/../../home/container.php');
  }

  public function post()
  {
    $course = $_POST['course'];
    $post = [];

    foreach (['title', 'code', 'department', 'class', 'semester', 'active', 'unit'] as $item) {
      if ($item === 'active') {
        $post[$item] = isset($course['active']) ? 1 : 0;
        continue;
      }

      $val = trim($course[$item]);

      if (in_array($item, ['code', 'title'])) $val = strtoupper($val);

      $post[$item] = $val;
    }

    $context = ['current_course' => $post,];

    $valid = self::courseExists($post);

    if (!$valid['valid']) {
      $context['messages'] = $valid['messages'];
      self::setPostSession(false, 'Course creation failed!', $context);
      $this->renderPage($context);
      return;
    }

    try {
      Courses1::createCourse($post);

      self::setPostSession(true, 'Course successfully created!', ['created_course' => $post], true);

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

    if ($redirect) header("Location: " . path_to_link(__DIR__ . '/..') . '?create-course');
  }
}
