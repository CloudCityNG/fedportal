<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../../admin_academics/models/StudentCourses.php');
require_once(__DIR__ . '/../../admin_academics/models/StudentCoursesUtilities.php');
require_once(__DIR__ . '/StudentRegisteredSessions.php');
require(__DIR__ . '/CourseFormPDF.php');

class ViewInfoController1
{

  private static $PRINT_COURSE_FORM = 'print-course-form';
  private static $VIEW_RESULTS = 'view-results';

  /**
   * @var string - registration/matriculation number of student
   */
  private $regNo;

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
    $this->sessionsSemestersData = StudentRegisteredSessions::getRegisteredSessions($this->regNo);
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
      $coursesWithGPA = StudentCoursesUtilities::addGpaInfo(['courses' => $courses]);

      //:TODO - make it possible to view gpa once all scores have been published for that semester
      unset($coursesWithGPA['gpa']);

      $resultDisplayTable = StudentCoursesUtilities::renderCoursesData(
        $sessionCode,
        $semesterNumber,
        $coursesWithGPA,
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
    $link_template = __DIR__ . '/view.php';
    $cssPath = path_to_link(__DIR__ . '/css/view-info.min.css', true);
    $jsPath = path_to_link(__DIR__ . '/js/view-info.min.js', true);
    require(__DIR__ . '/../home/container.php');
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') (new ViewInfoController1())->get();

