<?php

require_once(__DIR__ . '/helpers/auth.php');

require_once(__DIR__ . '/helpers/config.php');


function get_exams()
{

  $exam1 = $_POST['o_level_1'];

  $s1 = $_POST['o_level_1_score'];

  $scores1 = [];

  for ($i = 1; $i <= 9; $i++) {

    if (isset($s1["subject-$i"])) {

      $subject = $s1["subject-$i"];

      $grade = $s1["grade-$i"];

      if ($subject && $grade) {

        $scores1[] = [$subject, $grade];

      }
    }

  }

  $exam1['scores'] = $scores1;

  $exams[] = $exam1;


  if (isset($_POST['o_level_2'])) {

    $exam2 = $_POST['o_level_2'];

    $s2 = $_POST['o_level_2_score'];

    $scores2 = [];

    for ($i = 1; $i <= 9; $i++) {

      if (isset($s2["subject-$i"])) {

        $subject = $s2["subject-$i"];

        $grade = $s2["grade-$i"];

        if ($subject && $grade) {

          $scores2[] = [$subject, $grade];

        }
      }

    }

    $exam2['scores'] = $scores2;

    $exams[] = $exam2;
  }

  return json_encode($exams);

}

function post_secondary()
{//-------NOT REQUIRED--------
  $post_secondary_sch_name = trim($_POST['post_secondary_sch_name']);

  $post_secondary_sch_address = trim($_POST['post_secondary_sch_address']);

  $post_secondary_sch_start_date = trim($_POST['post_secondary_sch_start_date']);

  $post_secondary_sch_end_date = trim($_POST['post_secondary_sch_end_date']);

  $post_secondary_course_of_study = trim($_POST['post_secondary_course_of_study']);

  $post_secondary_qualification = trim($_POST['post_secondary_qualification']);

  $post_secondary = [];

  if (
    $post_secondary_sch_name &&
    $post_secondary_sch_address &&
    $post_secondary_sch_start_date &&
    $post_secondary_sch_end_date &&
    $post_secondary_course_of_study &&
    $post_secondary_qualification
  ) {

    $post_secondary['name'] = $post_secondary_sch_name;
    $post_secondary['address'] = $post_secondary_sch_address;
    $post_secondary['start_date'] = $post_secondary_sch_start_date;
    $post_secondary['end_date'] = $post_secondary_sch_end_date;
    $post_secondary['qualification'] = $post_secondary_qualification;

    return json_encode($post_secondary);
  }

  return "NULL";
}

$reg_no = trim($_POST['reg_no']);

$pry_edu = json_encode($_POST['pry_edu']);

$secondary_edu = json_encode($_POST['secondary_sch']);

$post_secondary = post_secondary();


$exams = get_exams();

$resource = @mysql_query(
  "insert into edu_history (reg_no, pry_edu, secondary_edu, o_level_scores, post_secondary) " .
  "VALUES ('$reg_no', '$pry_edu', '$secondary_edu', '$exams', '$post_secondary') ;"
);

//Check whether the query was successful or not
if ($resource) {
  $location = 'student_dashboard.php';
  $seconds = 1;
  header("Refresh: $seconds; URL=\"$location\"");
  echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"' . "\n" .
       '"http://www.w3.org/TR/html4/strict.dtd">' . "\n\n" .
       '<html lang="en">' . "\n" .
       '    <head>' . "\n" .
       '        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">' . "\n\n" .
       '        <link rel="stylesheet" type="text/css" href="stylesheet.css">' . "\n\n" .
       '    <title>Success</title>' . "\n\n" .
       '    </head>' . "\n\n" .
       '    <body>' . "\n\n" .
       '    <div id="success">' . "\n\n" .
       '        <h1>O-Levels Information Recorded Successfully!</h1>' . "\n\n" .
       '     </div>' . "\n\n" .
       '</html>';
  exit;
} else {
  //action failed
  echo("<h1>Cannot Submit Form. An error occurred " . @mysql_error() . "!</h1>");
  exit();
}
?>