<?php

require_once(__DIR__ . '/../login/auth.php');
include_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');
include_once(__DIR__ . '/../../admin_academics/models/Semester.php');
include_once(__DIR__ . '/../../admin_academics/models/StudentCourses.php');
include_once(__DIR__ . '/../../admin_academics/models/AcademicDepartment.php');
include_once(__DIR__ . '/../../admin_academics/models/Courses.php');
include_once(__DIR__ . '/../../admin_academics/models/StudentCurrent.php');
include_once(__DIR__ . '/../../admin_academics/models/AcademicLevels.php');
include_once(__DIR__ . '/../../helpers/get_student_profile_from_reg_no.php');
include_once(__DIR__ . '/../../helpers/get_photos.php');
include_once(__DIR__ . '/../../helpers/app_settings.php');
include_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');
include_once(__DIR__ . '/../../helpers/models/StudentBilling.php');

class CourseRegController1
{
  private $academic_year;
  private $reg_no;
  private $level;

  /**
   * The number (1st or second) of the semester in which the student is registering
   * @var string|number
   */
  private $semesterNumber;

  /**
   * An array of courses the students had signed up for
   * @var array
   */
  private $coursesChosen;
  private $dept_code;

  public function get()
  {
    $studentRegNo = $_SESSION['REG_NO'];

    if (!StudentProfile::exists($studentRegNo)) {
      self::exitOnError('You have not selected your department! Please complete bio data.');
    }

    $registerCoursesAcademicSession = AcademicSession::getCurrentSession();

    if (!$registerCoursesAcademicSession) {
      self::exitOnError('Current session not set. Please inform admin about this error.');
    }

    $registerCoursesAcademicYear = $registerCoursesAcademicSession['session'];
    $semesterFromDb = Semester::getCurrentSemester();

    if (!$semesterFromDb) {
      self::exitOnError('Current semester not set. Please inform admin about this error.');
    }

    $semester = $semesterFromDb['number'];
    $registerCoursesSemesterText = Semester::renderSemesterNumber($semester);
    $course_data = StudentCourses::getStudentCoursesForSemester([
      'reg_no' => $studentRegNo,
      'semester_id' => $semesterFromDb['id']
    ]);

    if (!empty($course_data)) {
      $printCourseFormLink = path_to_link(__DIR__ . '/../view_info1') .
        "?action=print-course-form&semester_id={$semesterFromDb['id']}&semester_number={$semester}&session={$registerCoursesAcademicSession['session']}";

      $link_template = __DIR__ . '/view-courses.php';

    } else {

      $profile = new StudentProfile($studentRegNo);
      $studentCourseRegViewContext = [
        'reg_no' => $studentRegNo,
        'dept_name' => AcademicDepartment::getDeptNameFromCode($profile->dept_code),
        'dept_code' => $profile->dept_code,
        'current-level' => $profile->getCurrentLevelDept($registerCoursesAcademicYear)['level'],
      ];

      $courses_for_semester = $this->getCoursesForSemesterDept($profile->dept_code, $semester);
      $link_template = __DIR__ . '/form.php';

    }

    $pageJsPath = path_to_link(__DIR__ . '/js/course-reg.js', true);
    $pageCssPath = path_to_link(__DIR__ . '/css/course-reg.min.css', true);
    require(__DIR__ . '/../home1/container.php');
  }

  /**
   * @param string $message
   */
  private static function exitOnError($message)
  {
    set_student_reg_form_completion_session1('error', $message);
    $home = STATIC_ROOT . 'student_portal/home1/';
    header("Location: {$home}");
  }

  /**
   * @param string $dept_code
   * @param string|int $semester
   * @return array
   */
  private function getCoursesForSemesterDept($dept_code, $semester)
  {
    $data = Courses1::getCourses([
      'department' => $dept_code,
      'semester' => $semester,
      'active' => 1
    ]);

    $result = [];

    foreach ($data as $row) {
      $data['code'] = $row['code'];
      $data['title'] = $row['title'];
      $data['unit'] = $row['unit'];
      $data['id'] = $row['id'];

      $class = $row['class'];

      if (!isset($result[$class])) $result[$class] = [$data];
      else $result[$class][] = $data;

    }

    return $result;
  }

  public function post()
  {
    $post = $_POST;

    if (isset($post['course_reg'])) {

      $this->reg_no = $post['reg_no'];
      $this->semesterNumber = $post['semester'];
      $this->academic_year = $post['academic_year'];
      $this->level = $post['level'];
      $this->coursesChosen = $post['course_reg'];
      $this->dept_code = $post['dept'];
      $this->insert_courses();

    } else {

      set_student_reg_form_completion_session1('error', 'Did you forget to select your courses?');
      $this->redirectToDashboard();
    }
  }

  private static function redirectToDashboard()
  {
    $home = STATIC_ROOT . 'student_portal/home1/';
    header("Location: {$home}");
  }

  public function insert_courses()
  {
    $count = count($this->coursesChosen);

    try {
      StudentCourses::bulkCreateForStudentForSemester(
        $this->coursesChosen,
        [
          'academic_year_code' => $this->academic_year,
          'reg_no' => $this->reg_no,
          'semester' => $this->semesterNumber,
          'level' => $this->level
        ]
      );

    } catch (PDOException $e) {
      logPdoException(
        $e,
        "An error occurred while inserting courses for student $this->reg_no",
        self::logger()
      );

      set_student_reg_form_completion_session1(
        'error',
        "Something went wrong. But this does not mean your courses have not been saved."
      );

      self::redirectToDashboard();
      return;
    }

    $this->setCurrentLevelDept();

    $bill = new StudentBilling();

    $bill->insert_bill($this->reg_no, $this->academic_year, $this->level, $this->dept_code);

    set_student_reg_form_completion_session1(
      'success',

      "You have registered for $count courses this semester. Click on
         view and print to print course registration form."
    );

    $this->publishScores();

    self::redirectToDashboard();
    return;

  }

  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger("CourseRegistrationPostController");
  }

  private function publishScores()
  {
    $currentSemester = Semester::getCurrentSemester();
    $studentCourses = StudentCourses::courseIdsAndSemesterExist([
      'course_ids' => array_keys($this->coursesChosen),
      'semester_id' => $currentSemester['id']
    ]);
    return StudentCourses::publishScores($studentCourses, $currentSemester['id'], $this->reg_no);
  }

  private function setCurrentLevelDept()
  {
    try {
      StudentCurrent::create([
        'reg_no' => $this->reg_no,
        'academic_year' => $this->academic_year,
        'level' => $this->level,
        'dept_code' => $this->dept_code,
        'dept_name' => AcademicDepartment::getDeptNameFromCode($this->dept_code)
      ]);

    } catch (PDOException $e) {
      logPdoException($e, "An error occurred while saving current academic parameters.", self::logger());
    }
  }

}

$course_reg_controller = new CourseRegController1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $course_reg_controller->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $course_reg_controller->post();
}
