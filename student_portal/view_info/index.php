<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../login/auth.php');
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../../admin_academics/models/StudentCourses.php');

class ViewInfoController
{

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
   * student has no registered courses
   */
  private $registeredSessions = null;

  public function __construct()
  {
    $this->regNo = $_SESSION[STUDENT_PORTAL_AUTH_KEY];
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
          $this->registeredSessions = [];

          foreach ($semestersWithSessions as $semester) {
            $session = $semester['session'];

            unset($semester['session']);

            $sessionCode = $session['session'];

            $semesterNumber = $semester['number'];
            if (!isset($this->registeredSessions[$sessionCode])) {
              $this->registeredSessions[$sessionCode] = [
                'current_level_dept' => StudentProfile::getCurrentForSession($this->regNo, $sessionCode),
                'session' => $session,
                'semesters' => [
                  $semesterNumber => $semester
                ]
              ];

            } else {
              $this->registeredSessions[$sessionCode]['semesters'][$semesterNumber] = $semester;
            }
          }

          ksort($this->registeredSessions);

          foreach ($this->registeredSessions as $sessionCode => $data) {
            $semesters = $data['semesters'];
            ksort($semesters);
            $this->registeredSessions[$sessionCode]['semesters'] = $semesters;
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
    $academicSessions = $this->registeredSessions;
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
