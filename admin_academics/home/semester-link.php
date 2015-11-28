<div class="side-nav semester-side-bar-nav">
  <span class="title">Manage Semester</span>

  <?php
  if (UserSession::isCapable('can_edit_semester') || UserSession::isCapable('can_create_semester')) {
    $semesterLink = path_to_link(__DIR__ . '/../semester/');
    echo "
      <div class='links'>
        <a class='link' href='{$semesterLink}'>Current and New Semester</a>
      </div>
    ";
  }
  ?>
</div>
