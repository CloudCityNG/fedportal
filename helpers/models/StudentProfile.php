<?php

include_once(__DIR__ . '/../databases.php');
include_once(__DIR__ . '/../app_settings.php');
include_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');


/**
 * @property float|int owing
 */
class StudentProfile
{

  private static $LOG_NAME = "student-profile";
  public $names;
  public $reg_no;
  public $photo;
  public $dept_code;

  function __construct($regNo)
  {

    $log = get_logger(self::$LOG_NAME);

    $this->reg_no = $regNo;

    $this->photo = self::getPhoto($regNo, true);

    $query = "SELECT first_name, surname, other_names, course
              FROM freshman_profile
              WHERE personalno = ?";

    try {
      $stmt = get_db()->prepare($query);

      $stmt->execute([$regNo]);

      $fetchedArray = $stmt->fetch();

      $this->names = $fetchedArray['first_name'] . ' ' . $fetchedArray['surname'] . ' ' . $fetchedArray['other_names'];

      $this->dept_code = $fetchedArray['course'];

      $stmt->closeCursor();

    } catch (PDOException $e) {

      logPdoException(
        $e,

        "Error while getting profile info for student $regNo.",

        $log
      );

    }
  }

  public static function getPhoto($regNo = null, $pathOnly = null)
  {
    $stmt = get_db()->prepare("SELECT nameofpic FROM pics WHERE personalno = ?");

    if (!$regNo) {

      if (session_status() === PHP_SESSION_NONE) {
        session_start();
      }

      if (isset($_SESSION['REG_NO'])) {
        $regNo = $_SESSION['REG_NO'];
      }
    }

    if ($stmt->execute([$regNo]) && $stmt->rowCount()) {

      $imagePath = 'photo_files/' . $stmt->fetch(PDO::FETCH_NUM)[0];

      $staticRootTrimmed = trim(STATIC_ROOT, "/\\");

      $staticRootPos = strpos(__DIR__, $staticRootTrimmed);

      $dirPathBeforeStaticRoot = substr(__DIR__, 0, $staticRootPos);

      if (file_exists($dirPathBeforeStaticRoot . $staticRootTrimmed . '/' . $imagePath)) {
        $imagePath = STATIC_ROOT . $imagePath;

        return $pathOnly ? $imagePath : "<img src='$imagePath'/>";

      }
    }

    return '';
  }

  public static function student_exists($regNo)
  {
    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("Attempting to confirm if student $regNo exists in database");

    $query = "SELECT Count(*) FROM freshman_profile WHERE personalno = ?";

    $query_param = [$regNo];

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($query_param)) {

      $log->addInfo("Query: \"$query\" successfully ran with param: ", $query_param);

      if ($stmt->fetchColumn()) {

        $log->addInfo("Student $regNo exists in database.");

        return true;

      }
    }
    $log->addWarning("Student {$regNo} not found in database.");
    return false;

  }

  public function get_owing()
  {
    include_once(__DIR__ . '/StudentBilling.php');

    $bill = new StudentBilling();

    return $bill->get_owing($this->reg_no);
  }

  public function getCompleteCurrentDetails()
  {
    return array_merge($this->toArray(), $this->get_current_level_dept());
  }

  private function toArray()
  {
    return [
      'names' => $this->names,

      'reg_no' => $this->reg_no,

      'photo' => $this->photo
    ];
  }

  public function get_current_level_dept($academic_year = null)
  {
    //if academic year is not given, it defaults to current academic year
    if (!$academic_year) {

      $academic_year = AcademicSession::getCurrentSession()['session'];
    }

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

    $params = [$this->reg_no, $academic_year];

    $log->addInfo(
      "About to get student current academic parameters with query: {$query} and params: ",
      $params
    );

    try {
      $stmt = get_db()->prepare($query);

      if ($stmt->execute($params)) {
        $returned_data = $stmt->fetch();
        $log->addInfo("Statement executed successfully, result is: ", [$returned_data]);
        $stmt->closeCursor();

        return $returned_data;
      }

      $log->addWarning("Statement did not execute correctly.");

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

  function __toString()
  {
    return json_encode($this->toArray());
  }

}
