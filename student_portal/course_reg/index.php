<?php

require_once(__DIR__ . '/../login/auth.php');
include_once(__DIR__ . '/../../helpers/databases.php');
include_once(__DIR__ . '/../../helpers/get_courses.php');
include_once(__DIR__ . '/../../helpers/course_exists.php');
include_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');
include_once(__DIR__ . '/../../admin_academics/models/Semester.php');
include_once(__DIR__ . '/../../admin_academics/models/StudentCourses.php');
include_once(__DIR__ . '/../../helpers/get_academic_departments.php');
include_once(__DIR__ . '/../../helpers/get_student_profile_from_reg_no.php');
include_once(__DIR__ . '/../../helpers/get_photos.php');
include_once(__DIR__ . '/../../helpers/get_academic_levels.php');
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

    $dept_name = get_academic_departments()[$dept_code];

    $semester = Semester::get_current_semester()['number'];

    $semester_text = Semester::render_semester_number($semester);

    $academic_year = AcademicSession::get_current_session()['session'];

    $course_data = course_exists($academic_year, $reg_no, $semester);

    $already_registered = !empty($course_data);

    $student = get_student_profile_from_reg_no($reg_no);

    $course_reg_post = STATIC_ROOT . 'student_portal/course_reg/course_reg_post';

    $view = $already_registered ? __DIR__ . '/view_print.php' : __DIR__ . '/form.php';

    require(__DIR__ . '/view.php');
  }

  public function post()
  {

  }
}

class CourseRegistration
{
  private $academic_year;

  private $reg_no;

  private $level;

  private $semester;

  private $courses_chosen;

  private $dept_code;

  private static $LOG_NAME = "course-registration-handler";

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

    $log = get_logger(self::$LOG_NAME);

    $count = count($this->courses_chosen);

    $data = [];

    foreach ($this->courses_chosen as $course_id) {
      $data[] = [
        'academic_year_code' => $this->academic_year,
        'reg_no' => $this->reg_no,
        'semester' => $this->semester,
        'course_id' => $course_id,
        'level' => $this->level
      ];
    }

    try {
      StudentCourses::bulk_create($data);

      $log->addInfo("$count courses successfully inserted into the database for student $this->reg_no.");

    } catch (PDOException $e) {
      logPdoException(
        $e,
        "An error occurred while inserting courses for student $this->reg_no",
        $log
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

  private function get_dept_name_from_code()
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $dept_name_query = "SELECT description FROM academic_departments WHERE  code = ?";

    $log->addInfo("About to retrieve department description for code
                   {$this->dept_code} with query: {$dept_name_query}");

    $name = '';

    try {

      $dept_name_stmt = $db->prepare($dept_name_query);

      $dept_name_stmt->execute([$this->dept_code]);

      $name = $dept_name_stmt->fetch(PDO::FETCH_NUM)[0];

      $log->addInfo("Department description successfully retrieved as {$name}");

    } catch (PDOException $e) {

      logPdoException($e, "An error occurred while retrieving department description.", $log);
    }

    return $name;

  }

  private function set_current_level_dept()
  {
    $name = $this->get_dept_name_from_code();

    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "INSERT INTO student_currents(reg_no, academic_year, level, dept_code, dept_name)
              VALUES (?, ?, ?, ?, ?)";

    $input_parameters = [
      $this->reg_no,
      $this->academic_year,
      $this->level,
      $this->dept_code,
      $name
    ];

    try {
      $log->addInfo("About to insert student current academic
                   parameters with query {$query} and params: ", $input_parameters);

      $stmt = $db->prepare($query);

      $stmt->execute($input_parameters);

      $log->addInfo("Current academic parameters successfully inserted.");

    } catch (PDOException $e) {

      logPdoException($e, "An error occurred while saving current academic parameters.", $log);
    }
  }

}

$course_reg_controller = new CourseRegController;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $course_reg_controller->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $course_reg = new CourseRegistration;

  $course_reg->insert_courses();
}
