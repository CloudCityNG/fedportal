<div class="side-nav view-courses-exams-side-bar-nav">
  <span class="title">Courses and exams view</span>

  <?php
  require_once(__DIR__ . '/StudentRegisteredSessions.php');
  if (session_status() === PHP_SESSION_NONE) session_start();
  $viewPrintUrl = path_to_link(__DIR__);

  foreach (StudentRegisteredSessions::getRegisteredSessions($_SESSION[STUDENT_PORTAL_AUTH_KEY])
           as $sessionCode => $sessionData) {
    $session = $sessionData['session'];
    $sessionStart = $session['start_date']->format('d-M-Y');
    $sessionEnd = $session['end_date']->format('d-M-Y');

    $sessionView = "
      <div class='side-nav-intermediate session-side-bar-nav'>
        <span class='title'>
          <span style='display: block;'>{$sessionCode} session / {$sessionData['current_level_dept']['level']}</span>
          <span style='color: #C4BDBD;'>({$sessionStart} to $sessionEnd)</span>
        </span>";

    foreach ($sessionData['semesters'] as $semesterNumber => $semesterData) {
      $semesterText = Semester::renderSemesterNumber($semesterNumber);
      $semesterId = $semesterData['id'];
      $queryStringSemester = "semester_id={$semesterId}&semester_number={$semesterNumber}&session={$sessionCode}";
      $printCourseFormUrl = "{$viewPrintUrl}?action=print-course-form&{$queryStringSemester}";
      $viewResultUrl = "{$viewPrintUrl}?action=view-results&{$queryStringSemester}";

      $sessionView .= "
      <div class='side-nav-intermediate semester-side-bar-nav'>
        <span class='title'>{$semesterText} semester</span>

        <div class='links semester-links'>
           <a class='link' href='{$printCourseFormUrl}'>
            Print Course Form
           </a>
           <a class='link' href='$viewResultUrl'>
            View Results
           </a>
      </div>
     </div>";
    }

    echo $sessionView . '</div>';
  }
  ?>
</div>
