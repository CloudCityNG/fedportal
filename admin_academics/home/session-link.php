<div class="side-nav session-side-bar-nav">

  <span class="title session-title">Manage Academic Sessions</span>

  <?php
  if (UserSession::isCapable('can_edit_session') || UserSession::isCapable('can_create_session')) {
    $sessionLink = path_to_link(__DIR__ . '/../academic_session');
    echo "
      <div class='links'>
        <a class='link' href='{$sessionLink}'>Current and New Academic Session</a>
      </div>
    ";
  }
  ?>
</div>
