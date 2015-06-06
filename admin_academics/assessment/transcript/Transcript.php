<?php
//require_once(__DIR__ . '/../../login/auth.php');
require(__DIR__ . '/TranscriptToPDF.php');

class AssessmentTranscriptController extends AssessmentController
{
  public static function post()
  {
    if (isset($_POST['student-transcript-query-submit'])) {
      $oldStudentTranscriptQueryData = $_POST['student-transcript-query'];

      $valid = self::getStudentProfile($oldStudentTranscriptQueryData);

      if (isset($valid['errors'])) {
        self::renderPage(
          $oldStudentTranscriptQueryData, ['messages' => $valid['errors'], 'posted' => false]
        );
        return;

      } else {

        $regNo = $oldStudentTranscriptQueryData['reg-no'];
        $coursesGrades = self::_groupCourses(
          StudentCourses::getStudentCourses(['reg_no' => $regNo], true, true),
          $regNo
        );

        $profile = $valid['student']->getCompleteCurrentDetails();

        self::renderPage(
          null, null, ['student' => $profile, 'sessions_semesters_courses_grades' => $coursesGrades]
        );
        return;
      }

    } else if (isset($_POST['student-transcript-download-submit'])) {
      $studentScoresData = json_decode($_POST['student-scores-data'], true);

      $transcriptToPDF = new TranscriptToPDF($studentScoresData);
      $transcriptToPDF->renderTranscript();
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
   *
   * @param string $regNo - Registration number of the student whose transcript we wish to get
   *
   * @return array - with the following structure:
   * [
   * 'session_code' => [
   *                      'current_level_dept' => [],
   *
   *                      'cgpa' => number,
   *
   *                      'semesters' => [
   *
   *                                        'semester_number' => [
   *                                                                'courses' => [courses...],
   *                                                                'semester_data' => [id=>id, created_at=> etc.],
   *                                                                'sum_units' => ,
   *                                                                'sum_points' => ,
   *                                                                'gpa' =>,
   *                                                                'cgpa' =>
   *                                                            ]
   *                                    ]
   *                  ]
   * ]
   *
   * @private
   */
  private static function  _groupCourses(array $courses, $regNo)
  {
    $coursesBySemester = [];

    foreach ($courses as $course) {
      $semesterId = $course['semester_id'];

      if (!isset($coursesBySemester[$semesterId])) $coursesBySemester[$semesterId] = ['courses' => [$course]];

      else $coursesBySemester[$semesterId]['courses'][] = $course;
    }

    $coursesBySessionsBySemester = [];

    foreach (Semester::getSemesterByIds(array_keys($coursesBySemester), true) as $semester) {
      $session = $semester['session'];
      unset($semester['session']);

      $sessionCode = $session['session'];

      $semesterId = $semester['id'];
      $semesterCoursesData = $coursesBySemester[$semesterId];

      $semesterCoursesData['semester_data'] = $semester;

      $semesterCoursesData = self::_addGpaInfo($semesterCoursesData);

      if (!isset($coursesBySessionsBySemester[$sessionCode])) {
        $coursesBySessionsBySemester[$sessionCode] = [
          'current_level_dept' => StudentProfile::getCurrentForSession($regNo, $sessionCode),

          'semesters' => [
            $semester['number'] => $semesterCoursesData
          ]
        ];

      } else {
        $coursesBySessionsBySemester[$sessionCode]['semesters'][$semester['number']] = $semesterCoursesData;
      }
    }

    /**
     * academic sessions must be displayed in client in ascending order
     */
    ksort($coursesBySessionsBySemester);

    foreach ($coursesBySessionsBySemester as $sessionCode => $sessionData) {
      $semesters = $sessionData['semesters'];

      /**
       * semesters must be displayed within session in ascending order of semester number
       * in client i.e 1st semester before 2nd semester
       */
      ksort($semesters);

      $semesterNumber = null;
      $cgpa = null;

      foreach ($semesters as $semesterNumber => $semesterData) {
        if ($semesterNumber == '2' && isset($semesters['1'])) {
          $gpa2 = floatval($semesters['2']['gpa']);
          $gpa1 = floatval($semesters['1']['gpa']);

          $cgpa = number_format(($gpa2 + $gpa1) / 2, 2);

          $semesters['2']['cgpa'] = $cgpa;
        }
      }

      $coursesBySessionsBySemester[$sessionCode]['cgpa'] = $cgpa ? $cgpa : $semesters[$semesterNumber]['gpa'];

      $coursesBySessionsBySemester[$sessionCode]['semesters'] = $semesters;
    }

    return $coursesBySessionsBySemester;
  }

  /**
   * Compute semester GPA for the student
   *
   * @param array $semesterCoursesData
   * @return array
   *
   * @private
   */
  private static function _addGpaInfo(array $semesterCoursesData)
  {
    $sumUnits = 0;
    $sumQualityPoints = 0;

    $courses = [];

    foreach ($semesterCoursesData['courses'] as $data) {
      $unit = floatval($data['unit']);
      $point = floatval($data['point']);
      $qualityPoint = $unit * $point;

      $data['quality_point'] = number_format($qualityPoint, 2);
      $courses[] = $data;

      $sumUnits += $unit;
      $sumQualityPoints += $qualityPoint;
    }

    $semesterCoursesData['courses'] = $courses;
    $semesterCoursesData['sum_units'] = number_format($sumUnits, 2);
    $semesterCoursesData['gpa'] = number_format($sumQualityPoints / $sumUnits, 2);
    $semesterCoursesData['sum_points'] = number_format($sumQualityPoints, 2);

    return $semesterCoursesData;
  }
}
