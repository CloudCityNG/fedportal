<?php
$createCoursePostMessage = '';

$postStatus = false;

if (isset($_SESSION['CREATE-COURSE-POST-KEY'])) {
  $postStatus = json_decode($_SESSION['CREATE-COURSE-POST-KEY'], true);
}

if ($postStatus) {
  if (!$postStatus['posted']) {
    $message = "<ul>\n";

    foreach ($postStatus['messages'] as $messageText) {
      $message .= "  <li>{$messageText}</li>\n";
    }

    $message .= "</ul>";

    $createCoursePostMessage = "
    <div class='alert alert-danger' role='alert'>
      <h4 style='text-align: center;'>{$postStatus['status']}</h4>

      <div>{$message}</div>
    </div>
    ";

  } else {
    $postedCourse = $postStatus['created_course'];
    $postedCourseActive = $postedCourse['active'] ? 'Active' : 'Inactive';

    $createCoursePostMessage = "
    <div class='alert alert-dismissible alert-success' role='alert'>
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
      </button>

      <h4 style='text-align: center;'>{$postStatus['status']}</h4>

      <div> <label>Title:</label> {$postedCourse['title']}</div>
      <div> <label>Code:</label> {$postedCourse['code']}</div>
      <div> <label>Unit:</label> {$postedCourse['unit']}</div>
      <div> <label>Department:</label> {$postedCourse['department']}</div>
      <div> <label>Level:</label> {$postedCourse['class']}</div>
      <div> <label>Semester:</label> {$postedCourse['semester']}</div>
      <div> <label>Active:</label> {$postedCourseActive}</div>
    </div>
    ";
  }
}

unset($_SESSION['CREATE-COURSE-POST-KEY']);
