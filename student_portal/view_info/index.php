<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../../admin_academics/models/StudentCourses.php');
require_once(__DIR__ . '/../../admin_academics/models/StudentCoursesUtilities.php');
require(__DIR__ . '/CourseFormPDF.php');

class ViewInfoController
{

  private static $PRINT_COURSE_FORM = 'print-course-form';
  private static $VIEW_RESULTS = 'view-results';
  /**
   * @var string - registration/matriculation number of student
   */
  private $regNo;
  /**
   * Array of semester IDs (database IDs) for which student has registered for courses. Defaults to null
   * if student has no registered courses.
   * @var null|array
   */
  private $semesterIds = null;
  /**
   * @var array|null - Academic sessions in which a student signed up for courses. Defaults to null if
   * student has no registered courses. It of the form:
   * 'sessionCode' => [
   *    'current_level_dept' => [],
   *    'session' => ['id' => string, 'session' => string, 'start_at' => Carbon],
   *    'semesters' => [
   *        'semester_number' => []
   *    ]
   *  ]
   */
  private $sessionsSemestersData = null;

  /**
   * @see StudentProfile::getCompleteCurrentDetails
   * @var array
   */
  private $studentProfile;

  public function __construct()
  {
    $this->regNo = $_SESSION[STUDENT_PORTAL_AUTH_KEY];
    $studentProfile = new StudentProfile($this->regNo);
    $this->studentProfile = $studentProfile->getCompleteCurrentDetails();
    $this->studentProfile['photo'] = $studentProfile->photo;
    $this->studentProfile['reg_no'] = $this->regNo;
    $this->setSemesterIds();
    $this->setRegisteredSessions();
  }

  /**
   * set the value of the @field $semesterIds
   * @see StudentCourses::getSemesters
   */
  private function setSemesterIds()
  {
    $errorMessage = "error occurred while getting semester IDs for which student '{$this->regNo}' signed up for courses";

    try {
      $this->semesterIds = StudentCourses::getSemesters($this->regNo);

    } catch (PDOException $e) {
      logPdoException(
        $e,
        "Database {$errorMessage}",
        self::logger()
      );

    } catch (Exception $e) {
      self::logGeneralError($e, $errorMessage);
    }
  }

  private static function logger()
  {
    return get_logger("StudentPortalViewInformationController");
  }

  private static function logGeneralError(Exception $e, $customMessage = '')
  {
    $customMessage = $customMessage ? "Unknown {$customMessage}: " : '';
    self::logger()->addError($customMessage . $e->getMessage());
  }

  private function setRegisteredSessions()
  {
    $errorMessage = "error occurred while retrieving academic sessions for which student '{$this->regNo}' registered for courses.";

    if ($this->semesterIds) {
      try {
        $semestersWithSessions = Semester::getSemesterByIds($this->semesterIds, true);

        if ($semestersWithSessions) {
          $this->sessionsSemestersData = [];

          foreach ($semestersWithSessions as $semester) {
            $session = $semester['session'];
            $semesterNumber = $semester['number'];

            unset($semester['session']);

            $sessionCode = $session['session'];

            if (!isset($this->sessionsSemestersData[$sessionCode])) {
              $this->sessionsSemestersData[$sessionCode] = [
                'current_level_dept' => StudentProfile::getCurrentForSession($this->regNo, $sessionCode),
                'session' => $session,
                'semesters' => [
                  $semesterNumber => $semester
                ]
              ];

            } else {
              $this->sessionsSemestersData[$sessionCode]['semesters'][$semesterNumber] = $semester;
            }
          }

          ksort($this->sessionsSemestersData);

          foreach ($this->sessionsSemestersData as $sessionCode => $data) {
            $semesters = $data['semesters'];
            ksort($semesters);
            $this->sessionsSemestersData[$sessionCode]['semesters'] = $semesters;
          }

        }

      } catch (PDOException $e) {
        logPdoException(
          $e,
          "Database {$errorMessage}",
          self::logger()
        );

      } catch (Exception $e) {
        self::logGeneralError($e, $errorMessage);
      }
    }
  }

  public function get()
  {
    parse_str($_SERVER['QUERY_STRING'], $query);

    if (count($query)) {
      $semesterId = $query['semester_id'];
      $semesterNumber = $query['semester_number'];
      $sessionCode = $query['session'];

      switch ($query['action']) {
        case self::$PRINT_COURSE_FORM:
          $this->printCourseForm($semesterId, $semesterNumber, $sessionCode);
          return;

        case self::$VIEW_RESULTS:
          $this->viewResults($semesterId, $semesterNumber, $sessionCode);
          return;
      }
    }

    $this->renderPage();
  }

  /**
   * put courses student has registered for the $semesterId argument in pdf and send to browser
   * @param string|int $semesterId
   * @param string|int $semesterNumber
   * @param string $sessionCode
   */
  private function printCourseForm($semesterId, $semesterNumber, $sessionCode)
  {
    $courses = StudentCourses::getStudentCoursesForSemester(['reg_no' => $this->regNo, 'semester_id' => $semesterId]);

    $data = [
      'courses' => $courses,
      'semester_number' => $semesterNumber,
      'session_data' => $this->sessionsSemestersData[$sessionCode]
    ];

    $form = new CourseFormPDF();
    $form->renderPage($this->studentProfile, $data);
  }

  /**
   * Make student courses and their grades viewable by students
   *
   * @param $semesterId
   * @param $semesterNumber
   * @param $sessionCode
   */
  private function viewResults($semesterId, $semesterNumber, $sessionCode)
  {
    $courses = null;
    $error = null;

    try {
      $courses = StudentCourses::getStudentCoursesForSemester(
        ['semester_id' => $semesterId, 'reg_no' => $this->regNo, 'publish' => 1], true, true
      );

    } catch (PDOException $e) {
      logPdoException(
        $e,
        'Exception occurred while attempting to get courses for semester ID ' . $semesterId . ' and student ' . $this->regNo,
        self::logger()
      );

      $error = 'DATABASE ERROR';

    } catch (Exception $e) {
      self::logGeneralError($e);
      $error = 'UNKNOWN ERROR';
    }

    $level = null;

    try {
      $currents = StudentProfile::getCurrentForSession($this->regNo, $sessionCode);

      if ($currents) {
        $level = $currents['level'];
      }

    } catch (PDOException $e) {
      logPdoException(
        $e,
        'Exception occurred while attempting to get level for semester ID ' . $semesterId . ' and student ' . $this->regNo,
        self::logger()
      );

      $error = 'DATABASE ERROR';

    } catch (Exception $e) {
      self::logGeneralError($e);

      $error = 'UNKNOWN ERROR';
    }

    $semesterText = Semester::renderSemesterNumber($semesterNumber);

    if (!$courses) {
      $error = "
        <h3>
        No results available for {$sessionCode} session and {$semesterText} semester or unknown semester ID '{$semesterId}'!
        </h3>
      ";

    } elseif (!$level) {
      $error = "<h3>No student level/class data for {$sessionCode} session or unknown session '{$sessionCode}'!</h3>";
    }

    if ($error) {
      $this->renderPage(['view_results_courses_data_view' => $error]);

    } else {
      $resultDisplayTable = StudentCoursesUtilities::renderCoursesData(
        $sessionCode,
        $semesterNumber,
        StudentCoursesUtilities::addGpaInfo(['courses' => $courses]),
        $level
      );

      $this->renderPage([
        'view_results_courses_data_view' => $resultDisplayTable
      ]);
    }
  }

  /**
   * @param array|null $renderPageArgs
   */
  private function renderPage(array $renderPageArgs = null)
  {
    $academicSessions = $this->sessionsSemestersData;

    $infoActions = [
      self::$PRINT_COURSE_FORM => self::$PRINT_COURSE_FORM,
      self::$VIEW_RESULTS => self::$VIEW_RESULTS
    ];

    $viewPrintUrl = path_to_link(__DIR__);
    $cssPath = path_to_link(__DIR__ . '/css/view-info.min.css');
    $jsPath = path_to_link(__DIR__ . '/js/view-info.min.js');
    require('view.php');
  }

  public function post()
  {

  }
}

$viewInfoCtrl = new ViewInfoController();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $viewInfoCtrl->get();

} else {
  $viewInfoCtrl->post();
}
