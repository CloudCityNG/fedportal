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

  public function get()
  {
    $studentRegNo = $_SESSION['REG_NO'];

    if (!StudentProfile::student_exists($studentRegNo)) {
      self::exitOnError('You have not selected your department! Please complete bio data.');
    }

    $academicYear = AcademicSession::getCurrentSession();

    if (!$academicYear) {
      self::exitOnError('Current session not set. Please inform admin about this error.');
    }

    $academicYear = $academicYear['session'];

    $semesterFromDb = Semester::getCurrentSemester();

    if (!$semesterFromDb) {
      self::exitOnError('Current semester not set. Please inform admin about this error.');
    }

    $profile = new StudentProfile($studentRegNo);

    $course_data = StudentCourses::getStudentCoursesForSemester([
      'reg_no' => $studentRegNo,
      'semester_id' => $semesterFromDb['id']
    ]);

    $semester = $semesterFromDb['number'];

    $studentCourseRegViewContext = [
      'reg_no' => $studentRegNo,
      'dept_name' => AcademicDepartment::getDeptNameFromCode($profile->dept_code),
      'dept_code' => $profile->dept_code,
      'current-level' => $profile->getCurrentLevelDept($academicYear)['level'],
      'semester-text' => Semester::renderSemesterNumber($semester)
    ];

    if (!empty($course_data)) {
      self::exitOnError(
        'You have signed up for courses for this semester. Please contact admin if you need to make changes'
      );

    } else {
      $courses_for_semester = $this->getCoursesForSemesterDept($profile->dept_code, $semester);
      $view = __DIR__ . '/form.php';
    }

    require(__DIR__ . '/view.php');
  }

  /**
   * @param string $message
   */
  private static function exitOnError($message)
  {
    set_student_reg_form_completion_session1('error', $message);
    $home = STATIC_ROOT . 'student_portal/home/';
    header("Location: {$home}");
  }

  /**
   * @param string $dept_code
   * @param string|int $semester
   * @return array
   */
  private function getCoursesForSemesterDept($dept_code, $semester)
  {
    $data = Courses1::getCoursesForSemesterDeptLevel([
      'department' => $dept_code,
      'semester' => $semester
    ]);

    $result = [];

    foreach ($data as $row) {
      $data['code'] = $row['code'];
      $data['title'] = $row['title'];
      $data['unit'] = $row['unit'];
      $data['id'] = $row['id'];

      $class = $row['class'];

      if (!isset($result[$class])) {
        $result[$class] = [$data];

      } else {
        $result[$class][] = $data;
      }

    }

    return $result;
  }
}

class CourseRegistrationPostController1
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

  function __construct()
  {
    $post = $_POST;

    if (isset($post['course_reg'])) {

      $this->reg_no = $post['reg_no'];

      $this->semesterNumber = $post['semester'];

      $this->academic_year = $post['academic_year'];

      $this->level = $post['level'];

      $this->coursesChosen = $post['course_reg'];

      $this->dept_code = $post['dept'];

    } else {

      set_student_reg_form_completion_session1('error', 'Did you forget to select your courses?');

      $this->redirectToDashboard();
      return;

    }
  }

  private static function redirectToDashboard()
  {
    $home = STATIC_ROOT . 'student_portal/home/';
    header("Location: {$home}");

    return;
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
  $course_reg = new CourseRegistrationPostController1;

  $course_reg->insert_courses();
}
