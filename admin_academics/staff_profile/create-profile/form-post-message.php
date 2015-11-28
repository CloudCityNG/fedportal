<?php
$createStaffProfilePostMessage = '';

if (isset($createStaffProfileContext['posted'])) {
  if (!$createStaffProfileContext['posted']) {
    $message = "<ul>\n";

    foreach ($createStaffProfileContext['messages'] as $messageText) {
      $message .= "  <li>{$messageText}</li>\n";
    }

    $message .= "</ul>";

    $createStaffProfilePostMessage = "
    <div class='alert alert-dismissible alert-danger' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>

          <h4 style='text-align: center;'>{$createStaffProfileContext['status']}</h4>

          <div>{$message}</div>
    </div>
    ";

  } else {
    $profile = $createStaffProfileContext['created_staff_profile'];
    $capabilities = 'None';
    $selectedCapabilities = isset($profile['capabilities']) ? $profile['capabilities'] : null;

    if (is_array($selectedCapabilities) && count($selectedCapabilities)) {
      $capabilities = "<ol>\n";

      foreach ($selectedCapabilities as $id => $name) {
        $capabilities .= "  <li>{$name}</li>\n";
      }

      $capabilities .= "</ol>";
    }

    $createStaffProfilePostMessage = "
    <div class='alert alert-dismissible alert-success' role='alert'>
          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button>

          <h4 style='text-align: center;'>{$createStaffProfileContext['status']}</h4>

          <div> <label>Username:</label> {$profile['username']}</div>
          <div> <label>First Name:</label> {$profile['first_name']}</div>
          <div> <label>Last Name:</label> {$profile['last_name']}</div>
          <div> <label>Capabilities:</label> {$capabilities}</div>
    </div>
    ";
  }
}
