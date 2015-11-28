<?php
require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../login/auth.php');
require(__DIR__ . '/grade-student/GradeStudent.php');
require(__DIR__ . '/transcript/Transcript.php');
require(__DIR__ . '/publish-results/PublishResults.php');

AdminAcademicsAuth::checkCapability('can_view_exams');

class AssessmentController
{

  private static function logger()
  {
    return get_logger('AssessmentController');
  }


  /**
   * When we do a post request, we must tell user status of post -
   * whether success or failure. The function does just that.
   *
   * @param array|null $postStatus - defaults to null in case of get requests. The array, if passed, must be of form:
   * [
   *  'posted' => bool,
   *  'messages' => [string, string, string....]
   * ]
   */
  public static function renderPostStatus(array $postStatus = null)
  {
    if ($postStatus) {
      $messages = '';

      foreach ($postStatus['messages'] as $message) {
        $messages .= "<li>{$message}</li>\n";
      }

      $errorLabel = '';

      if ($postStatus['posted']) {
        $alertClass = 'alert-success';

      } else {
        $alertClass = 'alert-danger';
        $errorLabel = '<h5>Following errors occurred:</h5>';
      }

      echo "
              <div class='alert alert-dismissible {$alertClass}' role='alert'>
                <button type=button class=close data-dismiss=alert aria-label=Close>
                  <span aria-hidden=true>&times;</span>
                </button>

                {$errorLabel}

                <ul>
                  {$messages}
                </ul>
              </div> ";
    }
  }

  /**
   * When a generic error is caught in a code block, log that error
   *
   * @param Exception $e
   * @param \Monolog\Logger $logger
   * @param string $message
   */
  protected static function logGeneralError(Exception $e, Monolog\Logger $logger, $message = '')
  {
    $logger->addInfo('Unknown Error: ' . $message);
    $logger->addInfo('Unknown Error: ' . $e->getMessage());
  }

  /**
   *
   * @param array $data - post data to be validated
   * @return array - in the form ['messages' => array[string1, ...], 'posted' => bool]
   */
  protected static function getStudentProfile(array $data)
  {
    $errorMessages = [];

    if (!isset($data['reg-no'])) {
      $errorMessages[] = 'Student registration number can not be null.';
    }

    $regNo = trim($data['reg-no']);

    if (empty($regNo)) {
      $errorMessages[] = 'Student registration number can not be empty.';
    }

    $student = new StudentProfile($regNo);

    if (!$student->regNoValid) {
      $errorMessages[] = "Student with registration number '{$regNo}' does not exist";
      return [
        'errors' => $errorMessages,
      ];
    }

    return [
      'student' => $student,
      'reg-no' => $regNo
    ];
  }

  /**
   * Jquery UI autocomplete plugin requires the source to be an object with keys 'label' and 'value'
   * @return array
   */
  protected static function getSemestersForJSAutoComplete()
  {
    $semesters = [];

    try {
      $semesters = Semester::getSemesters(10);

      if ($semesters) {
        $labelledSemesters = [];

        foreach ($semesters as $aSemester) {
          $aSemester['label'] = $aSemester['session']['session'] . ' - ' .
            Semester::renderSemesterNumber($aSemester['number']) . ' semester';

          self::logger()->addInfo("aSemester[label]: " . $aSemester['label']);

          $aSemester['value'] = $aSemester['id'];

          $labelledSemesters[] = $aSemester;
        }

        $semesters = $labelledSemesters;

        self::logger()->addInfo('Ten most recent semesters for jquery ui autocomplete: ', $semesters);
      }

    } catch (PDOException $e) {

      logPdoException(
        $e, 'Error occurred while retrieving the ten most recent academic sessions', self::logger());
    }

    return $semesters;
  }
}

switch ($_SERVER['QUERY_STRING']) {
  case 'transcripts': {
    AdminAcademicsAuth::checkCapability('can_gen_transcripts');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      AssessmentTranscriptController::renderPage();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
      AssessmentTranscriptController::post();
    }
    break;
  }

  case 'grade-students': {
    AdminAcademicsAuth::checkCapability('can_grade_students');
    $assessment = new AssessmentGradeStudentController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      AssessmentGradeStudentController::renderPage();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $assessment->post();
    }
    break;
  }

  case 'publish-results': {
    AdminAcademicsAuth::checkCapability('can_publish_results');
    $publisher = new PublishResultsController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $publisher->renderPage();

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $publisher->post();
    }
    break;
  }

  default:
    $home = path_to_link(__DIR__ . '/../home');
    header("Location: {$home}");
}
