<div class="row content-area">
  <div class="col-sm-5 side-nav">
    <?php
    if (!$academicSessions) {
      echo "You have not registered for any courses.";

    } else {
      $sessionView = "<ul id='sessions' class='filetree student-portal-view-info-sessions-semesters-container'>";
      $space = "&nbsp;&nbsp;";

      foreach ($academicSessions as $sessionCode => $sessionData) {
        $session = $sessionData['session'];
        $sessionStart = $session['start_date']->format('d-M-Y');
        $sessionEnd = $session['end_date']->format('d-M-Y');
        $sessionDates = "<span style='color: #C4BDBD;'>({$sessionStart} to $sessionEnd)</span>";

        $sessionLabel = "{$space}{$sessionCode} session / {$sessionData['current_level_dept']['level']} {$sessionDates}";

        $sessionView .= "
      <li class='student-portal-view-info-session'>
        <span class='folder student-portal-view-info-session-label'>{$sessionLabel}</span>\n";

        foreach ($sessionData['semesters'] as $semesterNumber => $semesterData) {
          $semesterText = Semester::renderSemesterNumber($semesterNumber);
          $semesterId = $semesterData['id'];

          $queryStringSemester = "semester_id={$semesterId}&semester_number={$semesterNumber}&session={$sessionCode}";
          $printCourseFormUrl = "{$viewPrintUrl}?action={$infoActions['print-course-form']}&{$queryStringSemester}";
          $viewResultUrl = "{$viewPrintUrl}?action={$infoActions['view-results']}&{$queryStringSemester}";

          $sessionView .= "
      <ul class='student-portal-view-info-semester-container'>
        <li>
          <span class='folder student-portal-view-info-semester-label'>{$space}{$semesterText} semester</span>
          <ul>
            <li>
              <span class='file'>{$space}
                <a href='{$printCourseFormUrl}' class='student-portal-view-semester-info-link' target='_blank'>
                  Print Course Form
                </a>
              </span>
            </li>

            <li>
              <span class='file'>{$space}
                <a href='$viewResultUrl' class='student-portal-view-semester-info-link'>
                  View Results
                </a>
              </span>
            </li>
          </ul>
        </li>
      </ul>\n";
        }

        $sessionView .= "</li>";
      }

      echo $sessionView . '</ul>';
    }
    ?>
  </div>

  <div class="col-sm-7">
    <?php
    if ($renderPageArgs && isset($renderPageArgs['view_results_courses_data_view'])) {
      echo $renderPageArgs['view_results_courses_data_view'];
    }
    ?>
  </div>
</div>
