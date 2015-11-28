<?php
/**
 * After the server is done processing a post request, the post will either succeed or fail.
 * This function tells user whether post succeeds or fails. If failure, messages are displayed
 * showing cause of failure.
 *
 * @param null|array $postStatus - This will be null if HTTP_REQUEST === GET, but will be an
 *                                 array for a post request
 *                                 $postStatus = [
 *                                                  'posted' => true or false,
 *                                                  'messages' => array of success or failure messages to display to
 *   users
 *                                               ]
 * @param string $formType - flag representing whether request emanated from current or new semester form.
 */
function renderPostStatus(array $postStatus = null, $formType)
{
  if ($postStatus and isset($postStatus[$formType])) {

    $postStatus = $postStatus[$formType];

    if ($postStatus['posted']) {
      $alertClass = 'alert-success';
      $status = $postStatus['messages'][0];
      $messages = '';

    } else {
      $alertClass = 'alert-danger';
      $status = "Semester not updated or created for following reasons:";

      $messages = '';

      foreach ($postStatus['messages'] as $message) {
        $messages .= "<li>{$message}</li>\n";
      }
    }

    echo "
      <div class='alert alert-dismissible {$alertClass}' role='alert'>
        <button type=button class=close data-dismiss=alert aria-label=Close>
          <span aria-hidden=true>&times;</span>
        </button>

        <h5>{$status}</h5>

        <ol>
          {$messages}
        </ol>
      </div> ";
  }
}

?>

<div class="semester-view">

  <span id="two-most-recent-sessions" style="display: none;">
    <?php echo json_encode($twoMostRecentSessions) ?>
  </span>

  <?php
  if (UserSession::isCapable('can_edit_semester')) require(__DIR__ . '/current-semester-view.php');

  renderPostStatus($postStatus, 'new_semester');

  if (UserSession::isCapable('can_create_semester')) require(__DIR__ . '/new-semester-view.php');
  ?>
</div>
