<?php
if (UserSession::isCapable('can_edit_session')) require(__DIR__ . '/current-session-view.php');
if (UserSession::isCapable('can_create_session')) require(__DIR__ . '/new-session-view.php');
?>
