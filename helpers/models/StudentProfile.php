<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 11-Feb-15
 * Time: 10:17 AM
 */

include_once(__DIR__ . '/../databases.php');

include_once(__DIR__ . '/../app_settings.php');

include_once(__DIR__ . '/../get_photos.php');

include_once(__DIR__ . '/AcademicYear.php');


/**
 * @property float|int owing
 */
class StudentProfile
{

  public $names;

  public $reg_no;

  public $photo;

  public $dept_code;

  private static $LOG_NAME = "student-profile";


  function __construct($reg_no)
  {

    $log = get_logger(self::$LOG_NAME);

    $db = get_db();

    $this->reg_no = $reg_no;

    $this->photo = get_photo($reg_no, true);

    $query = "SELECT first_name, surname, other_names, course
              FROM freshman_profile
              WHERE personalno = ?";

    $names = '';

    try {
      $stmt = $db->prepare($query);

      $stmt->execute([$reg_no]);

      $fetched_array = $stmt->fetch(PDO::FETCH_ASSOC);

      $first_name = $fetched_array['first_name'];

      if ($first_name) {
        $names = "{$first_name} ";
      }

      $this->names = $names . $fetched_array['surname'] . ' ' . $fetched_array['other_names'];

      $this->dept_code = $fetched_array['course'];

      $stmt->closeCursor();

    } catch (PDOException $e) {

      logPdoException(
        $e,

        "Error while getting profile info for student $reg_no.",

        $log
      );

    }
  }

  public function get_owing()
  {
    include_once(__DIR__ . '/StudentBilling.php');

    $bill = new StudentBilling();

    return $bill->get_owing($this->reg_no);
  }

  public function get_current_level_dept($academic_year = null)
  {
    //if academic year is not given, it defaults to current academic year
    if (!$academic_year) {

      $academic_years = new AcademicYear();

      $academic_year = $academic_years->get_current_year();
    }

    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $returned_data = [
      'level' => '',

      'dept_code' => '',

      'dept_name' => '',

      'academic_year',
    ];

    $query = "SELECT level, dept_code, dept_name, academic_year
              FROM student_currents
              WHERE reg_no = ? AND
              academic_year = ? ";

    try {
      $stmt = $db->prepare($query);

      $stmt->execute([$this->reg_no, $academic_year]);

      $log->addInfo("Query \"$query\" ran successfully.");


      if ($stmt->rowCount()) {
        $returned_data = $stmt->fetch(PDO::FETCH_ASSOC);
      }

      $stmt->closeCursor();

    } catch (PDOException $e) {

      logPdoException(
        $e,

        "Error occurred while retrieving current department,
         and level for student $this->reg_no with query: \"$query\"",

        $log
      );

    }

    return $returned_data;

  }

  public function get_complete_current_details()
  {
    return array_merge($this->to_array(), $this->get_current_level_dept());
  }

  public function get_billing_history()
  {
    $log = get_logger(self::$LOG_NAME);

    $db = get_db();

    $log->addInfo("About to retrieve payment history for student $this->reg_no.");

    $payments = [];

    $query = "SELECT * FROM student_payment WHERE reg_no = :reg_no ORDER BY created_at DESC ";

    try {

      $stmt1 = $db->prepare($query);

      if ($stmt1->execute([$this->reg_no])) {

        $log->addInfo("Query \"$query\" ran successfully");

        $payments = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        if ($payments) {

          $log->addInfo("Payment history found for student $this->reg_no: ", $payments);

        } else {
          $log->addWarning("No payment history found for student $this->reg_no");
        }

        $stmt1->closeCursor();

      } else {
        $log->addWarning(
          "Something is wrong.
           Query \"select * from student_payment where reg_no = '$this->reg_no'\" did not run."
        );
      }

    } catch (PDOException $e) {

      logPdoException(
        $e, "Error while retrieving payment history for student $this->reg_no.", $log
      );

    }

    return $payments ? $payments : [];

  }

  public static function student_exists($reg_no)
  {
    $log = get_logger(self::$LOG_NAME);

    $db = get_db();

    $log->addInfo("Attempting to confirm if student $reg_no exists in database");

    $query = "SELECT Count(*) FROM freshman_profile WHERE personalno = ?";

    $query_param = [$reg_no];

    $query_param_string = print_r($query_param, true);

    $stmt = $db->prepare($query);

    if ($stmt->execute($query_param)) {

      $log->addInfo("Query: \"$query\" successfully ran with param: \"$query_param_string\"");

      if ($stmt->fetchColumn()) {

        $log->addInfo("Student $reg_no exists in database.");

        return true;

      } else {

        $log->addWarning("Student $reg_no not found in database.");
      }

    }
    
    return false;

  }

  public function to_array()
  {
    return [
      'names' => $this->names,

      'reg_no' => $this->reg_no,

      'photo' => $this->photo
    ];
  }

  function __toString()
  {
    return json_encode($this->to_array());
  }

}
