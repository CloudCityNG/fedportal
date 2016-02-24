<?php
require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');
require_once(__DIR__ . '/../../admin_academics/models/StudentCourses.php');

function getRegisteredSessions($regNo)
{
  $logger = get_logger('GetSessionsForWhichStudentsRegistered');

  function logGeneralError(Exception $e, $customMessage = '')
  {
    global $logger;
    $customMessage = $customMessage ? "Unknown {$customMessage}: " : '';
    $logger->addError($customMessage . $e->getMessage());
  }

  $errorMessage = "error occurred while getting semester IDs for which student '{$regNo}'
    signed up for courses";

  $semesterIds = null;

  try {
    $semesterIds = StudentCourses::getSemesters($regNo);

  } catch (PDOException $e) {
    logPdoException($e, "Database {$errorMessage}", $logger);

  } catch (Exception $e) {
    logGeneralError($e, $errorMessage);
  }

  $errorMessage = "error occurred while retrieving academic sessions for which student '{$regNo}'
                     registered for courses.";
  $sessionsSemestersData = [];

  if ($semesterIds) {
    try {
      $semestersWithSessions = Semester::getSemesterByIds($semesterIds, true);

      if ($semestersWithSessions) {

        foreach ($semestersWithSessions as $semester) {
          $session = $semester['session'];
          $semesterNumber = $semester['number'];

          unset($semester['session']);

          $sessionCode = $session['session'];

          if (!isset($sessionsSemestersData[$sessionCode])) {
            $sessionsSemestersData[$sessionCode] = [
              'current_level_dept' => StudentProfile::getCurrentForSession($regNo, $sessionCode),
              'session' => $session,
              'semesters' => [
                $semesterNumber => $semester
              ]
            ];

          } else {
            $sessionsSemestersData[$sessionCode]['semesters'][$semesterNumber] = $semester;
          }
        }

        ksort($sessionsSemestersData);

        foreach ($sessionsSemestersData as $sessionCode => $data) {
          $semesters = $data['semesters'];
          ksort($semesters);
          $sessionsSemestersData[$sessionCode]['semesters'] = $semesters;
        }
      }

    } catch (PDOException $e) {
      logPdoException($e, "Database {$errorMessage}", $logger);

    } catch (Exception $e) {
      logGeneralError($e, $errorMessage);
    }
  }

  return $sessionsSemestersData;
}

?>

<div class="side-nav view-courses-exams-side-bar-nav">
  <span class="title">Courses and exams view</span>

  <?php
  if (session_status() === PHP_SESSION_NONE) session_start();
  $viewPrintUrl = path_to_link(__DIR__);

  foreach (getRegisteredSessions($_SESSION[STUDENT_PORTAL_AUTH_KEY]) as $sessionCode => $sessionData) {
    $session = $sessionData['session'];
    $sessionStart = $session['start_date']->format('d-M-Y');
    $sessionEnd = $session['end_date']->format('d-M-Y');

    $sessionView = "
      <div class='side-nav side-nav-intermediate session-side-bar-nav'>
        <span class='title'>
          <span style='display: block;'>{$sessionCode} session / {$sessionData['current_level_dept']['level']}</span>
          <span style='color: #C4BDBD;'>({$sessionStart} to $sessionEnd)</span>
        </span>

        <div class='links session-links'>";

    foreach ($sessionData['semesters'] as $semesterNumber => $semesterData) {
      $semesterText = Semester::renderSemesterNumber($semesterNumber);
      $semesterId = $semesterData['id'];

      $queryStringSemester = "semester_id={$semesterId}&semester_number={$semesterNumber}&session={$sessionCode}";
      $printCourseFormUrl = "{$viewPrintUrl}?action=print-course-form&{$queryStringSemester}";
      $viewResultUrl = "{$viewPrintUrl}?action='view-results&{$queryStringSemester}";

      $sessionView .= "
         <a class='link' href='{$printCourseFormUrl}' class='student-portal-view-semester-info-link' target='_blank'>
          Print Course Form
         </a>
         <a href='$viewResultUrl' class='student-portal-view-semester-info-link'>
          View Results
         </a>";
    }

    echo $sessionView . '</div></div>';
  }
  ?>
</div>
