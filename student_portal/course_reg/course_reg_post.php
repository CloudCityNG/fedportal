<?php
/**
 * User: maneptha
 * Date: 01-Feb-15
 * Time: 7:03 PM
 */

include_once(__DIR__ . '/../../helpers/databases.php');

include_once(__DIR__ . '/../../helpers/app_settings.php');

include_once(__DIR__ . '/../../vendor/autoload.php');

include_once(__DIR__ . '/../../helpers/app_settings.php');

require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');

include_once(__DIR__ . '/../../helpers/models/StudentBilling.php');


class CourseRegistration
{
  private $academic_year;

  private $reg_no;

  private $level;

  private $semester;

  private $courses_chosen;

  private $dept_code;

  private $log_name = "course-registration-handler";

  private static $LOG_NAME = "course-registration-handler";

  function __construct($post = null)
  {

    if ($post) {

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
  }

  public function insert_courses()
  {

    $db = get_db();

    $log = get_logger($this->log_name);

    $log->addInfo("Student $this->reg_no @ semester $this->semester, $this->academic_year
                   and $this->level about to register for the following course IDs: ");

    $log->addInfo("Courses: ", $this->courses_chosen);

    $course_id = '';

    $count = 0;

    try {
      $stmt = $db->prepare(
        "INSERT INTO student_courses(academic_year_code, reg_no, semester, course_id, level)
         VALUES (:academic_year_code, :reg_no, :semester, :COURSE_ID, :level)"
      );

      $stmt->bindValue(':academic_year_code', $this->academic_year);

      $stmt->bindValue(':reg_no', $this->reg_no);

      $stmt->bindValue(':semester', $this->semester);

      $stmt->bindValue(':level', $this->level);

      $stmt->bindParam(':COURSE_ID', $course_id);

      foreach ($this->courses_chosen as $course_id) {

        $stmt->execute();
      }

      $count = count($this->courses_chosen);

      $log->addInfo("$count courses successfully inserted into the database for student $this->reg_no.");

    } catch (PDOException $e) {

      $log->addError("An error occurred while inserting courses for student $this->reg_no");

      $log->addError($e->getMessage());

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $course_reg = new CourseRegistration($_POST);

  $course_reg->insert_courses();
}
