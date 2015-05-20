<?php

require(__DIR__ . '/TranscriptToPDF.php');

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

        self::renderPage(null, null, ['student' => $profile, 'courses' => $coursesGrades]);
        return;
      }

    } else if (isset($_POST['student-transcript-download-submit'])) {
      $studentScoresData = json_decode($_POST['student-scores-data'], true);
      //$studentScoresData['courses'] = self::_groupCourses($studentScoresData['courses']);

      new TranscriptToPDF($studentScoresData);
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

  /**
   * Group student courses into sessions and semesters
   *
   * @param array $courses
   * @return array - with the following structure:
   * [
   *    'session_code' => [
   *                        'semester_number' => [
   *                                                'courses' => [courses...],
   *                                                'semester_data' => [id=>id, created_at=> etc.]
   *                                            ]
   *                      ]
   * ]
   *
   * @private
   */
  private static function _groupCourses(array $courses)
  {
    $coursesBySemester = [];

    foreach ($courses as $course) {
      $semesterId = $course['semester_id'];

      if (!isset($coursesBySemester[$semesterId])) $coursesBySemester[$semesterId] = ['courses' => [$course]];

      else $coursesBySemester[$semesterId]['courses'][] = $course;
    }

    $coursesBySessionsBySemester = [];

    foreach (Semester::getSemesterByIds(array_keys($coursesBySemester), true) as $data) {
      $session = $data['session'];
      unset($data['session']);

      $sessionCode = $session['session'];

      $semesterId = $data['id'];
      $coursesBySemester[$semesterId]['semester_data'] = $data;

      if (!isset($coursesBySessionsBySemester[$sessionCode])) {
        $coursesBySessionsBySemester[$sessionCode] = [
          $data['number'] => $coursesBySemester[$semesterId]
        ];

      } else {
        $coursesBySessionsBySemester[$sessionCode][$data['number']] = $coursesBySemester[$semesterId];
      }
    }


    //foreach ($coursesBySessionsBySemester as $sessionCode => $sessionSemestersAndCourses) {
    //  echo $sessionCode;
    //  echo "<br/><br/>";
    //
    //  foreach ($sessionSemestersAndCourses as $semesterNumber => $semesterCoursesAndData) {
    //    echo $semesterNumber;
    //    echo "<br/>";
    //
    //    print_r($semesterCoursesAndData);
    //    echo "<br/><br/><br>";
    //  }
    //}
    //
    //exit;
    return $coursesBySessionsBySemester;
  }
}
