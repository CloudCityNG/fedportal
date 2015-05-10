<?php

class AssessmentTranscriptController extends AssessmentController
{
  public static function post()
  {
    if (isset($_POST['student-transcript-query-submit'])) {
      $oldStudentTranscriptQueryData = $_POST['student-transcript-query'];

      $valid = self::validatePostedRegNo($oldStudentTranscriptQueryData);

      if (isset($valid['errors'])) {
        self::renderPage(
          $oldStudentTranscriptQueryData, ['messages' => $valid['errors'], 'posted' => false]
        );
        return;

      } else {

        $regNo = $oldStudentTranscriptQueryData['reg-no'];
        $coursesGrades = StudentCourses::getStudentCourses(['reg_no' => $regNo], true, true);

        $profile = (new StudentProfile($regNo))->getCompleteCurrentDetails();

        self::renderPage(null, null, ['student' => $profile, 'courses' => $coursesGrades]); return;

//        print_r($profile);
      }
    }
  }

  public static function renderPage(
    array $oldStudentTranscriptQueryData = null,
    array $postStatus = null,
    array $studentScoresData = null
  )
  {
    $currentPage = [
      'title' => 'assessment',

      'link' => 'transcripts'
    ];

    $link_template = __DIR__ . '/transcript-partial.php';

    $pageJsPath = path_to_link(__DIR__ . '/js/transcript.min.js');

    $pageCssPath = path_to_link(__DIR__ . '/css/transcript.min.css');

    require(__DIR__ . '/../../home/container.php');
  }
}
