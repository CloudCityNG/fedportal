<?php

require(__DIR__ . '/grade-student/GradeStudent.php');
require(__DIR__ . '/transcript/Transcript.php');

class AssessmentController
{
  /**
   * When we do a post request, we must tell user status of post -
   * whether success or failure. The function does just that.
   *
   * @param array|null $postStatus - defaults to null in case of get requests
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
   *
   * @param array $data - post data to be validated
   * @return array - in the form ['messages' => array[string1, ...], 'posted' => bool]
   */
  protected static function validatePostedRegNo(array $data)
  {
    $errorMessages = [];

    if (!isset($data['reg-no'])) {
      $errorMessages[] = 'Student registration number can not be null.';
    }

    $regNo = trim($data['reg-no']);

    if (empty($regNo)) {
      $errorMessages[] = 'Student registration number can not be empty.';
    }

    $student = StudentProfile1::getStudentByRegNo($regNo);

    if (!$student) {
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
}

if ($_SERVER['QUERY_STRING'] === 'transcripts') {

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    AssessmentTranscriptController::renderPage();

  } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    AssessmentTranscriptController::post();
  }
} else {
  $assessment = new AssessmentGradeStudentController();

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    AssessmentGradeStudentController::renderPage();

  } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assessment->post();
  }

}
