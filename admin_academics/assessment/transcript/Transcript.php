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
        $coursesGrades = self::_groupCourses(
          StudentCourses::getStudentCourses(['reg_no' => $regNo], true, true)
        );

        $profile = (new StudentProfile($regNo))->getCompleteCurrentDetails();

        self::renderPage(
          null, null, ['student' => $profile, 'sessions_semesters_courses_grades' => $coursesGrades]
        );
        return;
      }

    } else if (isset($_POST['student-transcript-download-submit'])) {
      $studentScoresData = json_decode($_POST['student-scores-data'], true);

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

    $pageCssPath = path_to_link(__DIR__ . '/css/grade-student-transcript.min.css');

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

    return $coursesBySessionsBySemester;
  }
}
