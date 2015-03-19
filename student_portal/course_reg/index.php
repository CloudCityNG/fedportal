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

class CourseRegController
{
  private static $LOG_NAME = 'Course-registration';

  public function get()
  {
    $reg_no = $_SESSION['REG_NO'];

    if (!StudentProfile::student_exists($reg_no)) {

      set_student_reg_form_completion_session1(
        'error',
        'You have not selected your department! Please complete bio data.');

      $home = STATIC_ROOT . 'student_portal/home/';
      header("Location: {$home}");
    }

    $profile = new StudentProfile($reg_no);

    $dept_code = $profile->dept_code;

    $dept_name = AcademicDepartment::get_dept_name_from_code($dept_code);

    $semester = Semester::get_current_semester()['number'];

    $semester_text = Semester::render_semester_number($semester);

    $academic_year = AcademicSession::get_current_session()['session'];

    $course_data = StudentCourses::get_student_current_courses([
      'reg_no' => $reg_no, 'semester' => $semester, 'session' => $academic_year
    ]);

    if (!empty($course_data)) {
      $student = get_student_profile_from_reg_no($reg_no);
      $view = __DIR__ . '/view_print.php';

    } else {
      $courses_for_semester = $this->get_courses_for_semester_dept($dept_code, $semester);
      $view = __DIR__ . '/form.php';
    }

    require(__DIR__ . '/view.php');
  }

  private function get_courses_for_semester_dept($dept_code, $semester)
  {
    $data = Courses1::get_courses_for_semester_and_dept([
      'department' => $dept_code, 'semester' => $semester
    ]);

    $result = [];

    foreach ($data as $row) {
      $data['code'] = $row['code'];
      $data['title'] = $row['title'];
      $data['unit'] = $row['unit'];
      $data['id'] = $row['id'];

      $class = $row['class'];

      if (array_key_exists($class, $result)) {
        $result[$class][] = $data;

      } else {
        $result[$class] = [$data];
      }

    }

    return $result;
  }
}

class CourseRegistrationPostController
{
  private $academic_year;

  private $reg_no;

  private $level;

  private $semester;

  private $courses_chosen;

  private $dept_code;

  private static $LOG_NAME = "CourseRegistrationPostController";

  function __construct()
  {
    $post = $_POST;

    if (isset($post['course_reg'])) {

      $this->reg_no = $post['reg_no'];

      $this->semester = $post['semester'];

      $this->academic_year = $post['academic_year'];

      $this->level = $post['level'];

      $this->courses_chosen = $post['course_reg'];

      $this->dept_code = $post['dept'];

    } else {

      set_student_reg_form_completion_session1('error', 'Did you forget to select your courses?');

      $this->redirect_to_dashboard();
      return;

    }
  }

  public function insert_courses()
  {
    $count = count($this->courses_chosen);

    try {
      StudentCourses::bulk_create_for_student_for_semester(
        $this->courses_chosen,
        [
          'academic_year_code' => $this->academic_year,
          'reg_no' => $this->reg_no,
          'semester' => $this->semester,
          'level' => $this->level
        ]
      );

    } catch (PDOException $e) {
      logPdoException(
        $e,
        "An error occurred while inserting courses for student $this->reg_no",
        get_logger(self::$LOG_NAME)
      );

      set_student_reg_form_completion_session1(
        'error',
        "Something went wrong. But this does not mean your courses have not been saved."
      );

      $this->redirect_to_dashboard();
      return;
    }

    $this->set_current_level_dept();

    $bill = new StudentBilling();

    $bill->insert_bill($this->reg_no, $this->academic_year, $this->level, $this->dept_code);

    set_student_reg_form_completion_session1(
      'success',

      "You have registered for $count courses this semester. Click on
         view and print to print course registration form."
    );

    $this->redirect_to_dashboard();
    return;

  }

  private function redirect_to_dashboard()
  {
    $home = STATIC_ROOT . 'student_portal/home/';
    header("Location: {$home}");

    return;
  }

  private function set_current_level_dept()
  {
    try {
      StudentCurrent::create([
        'reg_no' => $this->reg_no,
        'academic_year' => $this->academic_year,
        'level' => $this->level,
        'dept_code' => $this->dept_code,
        'dept_name' => AcademicDepartment::get_dept_name_from_code($this->dept_code)
      ]);

    } catch (PDOException $e) {
      logPdoException($e, "An error occurred while saving current academic parameters.", get_logger(self::$LOG_NAME));
    }
  }

}

$course_reg_controller = new CourseRegController;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $course_reg_controller->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $course_reg = new CourseRegistrationPostController;

  $course_reg->insert_courses();
}
