<?php

require_once(__DIR__ . '/../databases.php');
require_once(__DIR__ . '/../app_settings.php');
require_once(__DIR__ . '/../SqlLogger.php');
require_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');

/**
 * @property float|int owing
 */
class StudentProfile
{
  /**
   * Name of student, in the form: first_name [optional middle_name] last_name
   * @var string
   */
  public $names;

  /**
   * student matriculation or registration number
   * @var string
   */
  public $reg_no;

  /**
   * absolute path to student
   * @var string - the URL of the student image
   */
  public $photo;

  /**
   * The code for the student department, and not the name of the department
   * @var string
   */
  public $dept_code;

  /**
   * academic session in which the student was admitted e.g 2013/2014.
   *
   * @var string
   */
  public $admissionSession;

  /**
   * Users of this class can check this variable to know if student's registration number they supplied
   * to the constructor is valid before they access other public methods and fields of this class
   *
   * @var bool
   */
  public $regNoValid = false;

  function __construct($regNo)
  {
    $this->reg_no = $regNo;

    $this->initProfile();
  }

  private function initProfile()
  {
    $query = "SELECT * FROM freshman_profile WHERE personalno = ?";
    $param = [$this->reg_no];
    $logger = new SqlLogger(self::logger(), "Get student bio data/profile", $query, $param);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $logger->statementSuccess();
      $fetchedArray = $stmt->fetch();

      if ($fetchedArray) {
        $logger->dataRetrieved($fetchedArray);
        $this->regNoValid = true;
        $this->names = $fetchedArray['first_name'] . ' ' . $fetchedArray['other_names'] . ' ' . $fetchedArray['surname'];
        $this->dept_code = $fetchedArray['course'];
        $this->admissionSession = $fetchedArray['currentsession'];
        $this->photo = self::getPhoto($this->reg_no, true);
      }

      $logger->noData();
      $stmt->closeCursor();
    }
  }

  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger("StudentProfileModel");
  }

  public static function setStudentLoginSession($regNo)
  {
    if (session_status() === PHP_SESSION_NONE) session_start();

    session_regenerate_id();
    unset($_SESSION[USER_AUTH_SESSION_KEY]);
    $student = self::getStudentProfile($regNo);
    $_SESSION[USER_AUTH_SESSION_KEY] = json_encode($student);
    $_SESSION[STUDENT_PORTAL_AUTH_KEY] = $regNo;
    $_SESSION['LAST-ACTIVITY-REG_NO'] = time();
    session_write_close();
    header('location: ' . STATIC_ROOT . 'student_portal/home/');
  }

  /**
   * @param $regNo
   * @return array|null
   */
  public static function getStudentProfile($regNo)
  {
    $query = "SELECT * FROM freshman_profile WHERE personalno=:reg_no";
    $param = ['reg_no' => $regNo];
    $sqlLogger = new SqlLogger(self::logger(), 'Get student profile: ', $query, $param);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $sqlLogger->statementSuccess();
      $result = $stmt->fetch();

      if (is_array($result) && count($result)) {
        $sqlLogger->dataRetrieved($result);
        $result['username'] = $result['personalno'];
        $result['last_name'] = isset($result['surname']) ? $result['surname'] : '';
        return $result;
      }
    }
    $sqlLogger->noData();
    return null;
  }

  /**
   * @param null|string $regNo - student registration or matriculation number.
   * If not given, we try to get it from browser session. Getting the registration number from browser's session
   * is because this is a legacy code. This is not recommended.
   *
   * @param bool $pathOnly - if true, we return the html image tag with the URL to student's image as the src attribute
   * otherwise we return only the URL
   *
   * @return string - the URL to the student's image or the the html image tag with the URL
   */
  public static function getPhoto($regNo = null, $pathOnly = false)
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
    $logger = new SqlLogger(
      self::logger(),
      'Get the level and department a student was during a particular academic year',
      $query,
      $params
    );
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $logger->statementSuccess();
      $result = $stmt->fetch();

      if ($result) {
        $logger->dataRetrieved($result);
        return $result;
      }
    }

    $logger->noData();
    return null;
  }

  /**
   * Get all academic sessions in which a student has registered for courses
   *
   * @param string $regNo - the student registration/matriculation number
   * @return array|null
   */
  public static function getRegisteredSessions($regNo)
  {
    $query = "SELECT * FROM student_currents WHERE  reg_no = '{$regNo}'";
    $logger = new SqlLogger(
      self::logger(), 'Get all academic sessions in which a student signed up for courses', $query
    );
    $stmt = get_db()->query($query);

    if ($stmt) {
      $logger->statementSuccess();
      $result = $stmt->fetchAll();

      if (count($result)) {
        $logger->dataRetrieved($result);
        return $result;
      }
    }

    $logger->noData();
    return null;
  }

  public static function exists($regNo)
  {
    $query = "SELECT Count(*) FROM freshman_profile WHERE personalno = ?";
    $param = [$regNo];
    $logger = new SqlLogger(self::logger(), 'Confirm if student exists in database', $query, $param);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $logger->statementSuccess();

      if ($stmt->fetchColumn()) {
        $logger->dataRetrieved(true);
        return true;
      }
    }

    $logger->noData();
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

      'photo' => $this->photo,

      'admission_session' => $this->admissionSession
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
