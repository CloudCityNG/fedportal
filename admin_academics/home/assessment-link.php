<div class="side-nav side-nav-exams-assessment">
  <span class="title">Exams And Assessments</span>

  <div class="links">
    <?php
    if (UserSession::isCapable('can_grade_students')) {
      $gradeLink = path_to_link(__DIR__ . '/../assessment/') . '?grade-students';
      echo "<a class='link' href='{$gradeLink}'>Grade Students</a>";
    }

    if (UserSession::isCapable('can_gen_transcripts')) {
      $transcriptsLink = path_to_link(__DIR__ . '/../assessment/') . '?transcripts';
      echo "<a class='link' href='{$transcriptsLink}'>Transcripts</a>";
    }

    if (UserSession::isCapable('can_publish_results')) {
      $publishLink = path_to_link(__DIR__ . '/../assessment/') . '?publish-results';
      echo "<a class='link' href='{$publishLink}'>Publish Results</a>";
    }
    ?>
  </div>
</div>
