<?php

require_once(__DIR__ . '/../databases.php');
require_once(__DIR__ . '/../app_settings.php');
require_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');

/**
 * @property float|int owing
 */
class StudentProfile
{
  public $names;
  public $reg_no;
  public $photo;
  public $dept_code;

  function __construct($regNo)
  {
    $this->reg_no = $regNo;

    $this->photo = self::getPhoto($regNo, true);

    $query = "SELECT first_name, surname, other_names, course
              FROM freshman_profile
              WHERE personalno = ?";

    try {
      $stmt = get_db()->prepare($query);

      $stmt->execute([$regNo]);

      $fetchedArray = $stmt->fetch();

      $this->names = $fetchedArray['first_name'] . ' ' . $fetchedArray['other_names'] . ' ' . $fetchedArray['surname'];

      $this->dept_code = $fetchedArray['course'];

      $stmt->closeCursor();

    } catch (PDOException $e) {

      logPdoException($e, "Error while getting profile info for student $regNo.", self::logger());
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

  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('StudentProfileModel');
  }

  /**
   * Given a student's registration number, find the level and
   * department the student was during the given session code.
   *
   * @param string $regNo - the student registration number
   * @param string $sessionCode - the session code e.g 2014/2015
   *
   * @return array|null
   */
  public static function getCurrentForSession($regNo, $sessionCode)
  {
    $query = "SELECT * FROM student_currents WHERE reg_no = ? AND academic_year = ?";

    $params = [$regNo, $sessionCode];

    self::logger()->addInfo(
      "About to get the level and department a student was during a particular academic year with query: {$query} " .
      'and params: ', $params
    );

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $result = $stmt->fetch();

      if ($result) {
        self::logger()->addInfo('Statement executed successfully, result is ', $result);
        return $result;
      }
    }

    self::logger()->addWarning(
      'Unable to get the level and department a student was during a particular academic year'
    );

    return null;
  }

  public static function student_exists($regNo)
  {
    self::logger()->addInfo("Attempting to confirm if student $regNo exists in database");

    $query = "SELECT Count(*) FROM freshman_profile WHERE personalno = ?";

    $query_param = [$regNo];

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($query_param)) {

      self::logger()->addInfo("Query: \"$query\" successfully ran with param: ", $query_param);

      if ($stmt->fetchColumn()) {

        self::logger()->addInfo("Student $regNo exists in database.");

        return true;

      }
    }

    self::logger()->addWarning("Student {$regNo} not found in database.");
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
    $currentLevelDept = $this->getCurrentLevelDept();

    if (!$currentLevelDept) {
      require_once(__DIR__ . '/../../admin_academics/models/AcademicDepartment.php');

      $currentLevelDept = [
        'level' => 'Unknown',
        'dept_code' => $this->dept_code,
        'dept_name' => AcademicDepartment::getDeptNameFromCode($this->dept_code),
        'academic_year' => 'Unknown'
      ];
    }

    return array_merge($this->toArray(), $currentLevelDept);
  }

  /**
   * @param null|string $academicYear - e.g 2014/2015. If academic year is not given, we default
   * to current session
   *
   * @return array|null - return an array of current level and department could be found successfully
   * the returned array is in the form
   * [
   *    'level' => string,
   *    dept_code => string,
   *    dept_name => string,
   *    academic_year => string
   * ]
   * return null of current level and department can not be found.
   */
  public function getCurrentLevelDept($academicYear = null)
  {
    $db = get_db();

    $academicYearArg = "\$academicYear '{$academicYear}' argument passed to method " . __METHOD__;

    if (!$academicYear) {

      $currentSession = AcademicSession::getCurrentSession();
      if ($currentSession) {
        $academicYear = $currentSession['session'];
        $academicYearArg = 'call to database for current session';
      }
    }

    if ($academicYear) {
      $query1 = "SELECT level, dept_code, dept_name, academic_year
                FROM student_currents
                WHERE reg_no = ? AND
                academic_year = ? ";

      $params1 = [$this->reg_no, $academicYear];

      self::logger()->addInfo(
        "About to get student current academic parameters using '{$academicYearArg}', with query: {$query1} and params: ",
        $params1
      );


      $stmt = $db->prepare($query1);

      if ($stmt->execute($params1) && $stmt->rowCount()) {
        $result = $stmt->fetch();

        self::logger()->addInfo(
          "Statement executed successfully. Current level and department were found using '{$academicYearArg}', result is: ",
          $result
        );

        $stmt->closeCursor();

        return $result;
      }
    }

    $query2 = "SELECT level, dept_code, dept_name, academic_year
               FROM student_currents
               WHERE reg_no = ?
               ORDER BY academic_year DESC LIMIT 1";

    $param2 = [$this->reg_no];

    self::logger()->addWarning(
      "Student's current level and department could not be found using the supplied argument to method " . __METHOD__ .
      ' or current session. We will go ahead and retrieve level and department for most recent session for which ' .
      "student applied for courses using query: {$query2} and param: ", $param2
    );

    $stmt = $db->prepare($query2);

    if ($stmt->execute($param2) && $stmt->rowCount()) {
      $result = $stmt->fetch();

      self::logger()->addInfo(
        'Statement executed successfully. Current level and department were found for most recent session for which ' .
        'student applied for courses, result is: ', $result
      );

      $stmt->closeCursor();

      return $result;
    }

    self::logger()->addWarning("Student's current level and department could not be found.");

    return null;
  }

  private function toArray()
  {
    return [
      'names' => $this->names,

      'reg_no' => $this->reg_no,

      'photo' => $this->photo
    ];
  }

  public function get_billing_history()
  {
    $db = get_db();

    self::logger()->addInfo("About to retrieve payment history for student $this->reg_no.");

    $payments = [];

    $query = "SELECT * FROM student_payment WHERE reg_no = :reg_no ORDER BY created_at DESC ";

    try {

      $stmt1 = $db->prepare($query);

      if ($stmt1->execute([$this->reg_no])) {

        self::logger()->addInfo("Query \"$query\" ran successfully");

        $payments = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        if ($payments) {

          self::logger()->addInfo("Payment history found for student $this->reg_no: ", $payments);

        } else {
          self::logger()->addWarning("No payment history found for student $this->reg_no");
        }

        $stmt1->closeCursor();

      } else {
        self::logger()->addWarning(
          "Something is wrong.
           Query \"select * from student_payment where reg_no = '$this->reg_no'\" did not run."
        );
      }

    } catch (PDOException $e) {

      logPdoException(
        $e, "Error while retrieving payment history for student $this->reg_no.", self::logger()
      );

    }

    return $payments ? $payments : [];
  }

  function __toString()
  {
    return json_encode($this->toArray());
  }

}
