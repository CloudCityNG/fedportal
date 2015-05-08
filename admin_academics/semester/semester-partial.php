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
 *                                                  'messages' => array of success or failure messages to display to users
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

  <div class="panel panel-default current-semester-panel">
    <div class="panel-heading">
      <h1 class="panel-title">Current Semester</h1>
    </div>

    <div class="panel-body">
      <?php
      if ($oldCurrentSemesterData || $current_semester) {
        renderPostStatus($postStatus, 'current_semester');

        require __DIR__ . '/current-semester-form.php';

      } else if ($semestersInCurrentSession) {
        require(__DIR__ . '/current-semester-select-update.php');

      } else {
        echo 'Semester or session not set';
      }
      ?>
    </div>

    <?php
    if ($oldCurrentSemesterData || $current_semester) {
      echo '<div class="panel-footer">
                <span class="glyphicon glyphicon-edit current-semester-edit-trigger"
                data-toggle="tooltip" title="Edit semester"
                id="semester-form-edit-icon1"></span>
            </div>';
    }
    ?>
  </div>

  <?php
  renderPostStatus($postStatus, 'new_semester');
  ?>

  <div>
    <?php
    if (!AcademicSession::getCurrentSession()) {
      echo 'New session has not been set. New semester is not available';

    } else {
      require __DIR__ . '/new-semester-form.php';
    }
    ?>
  </div>
</div>
