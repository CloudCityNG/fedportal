<?php

require_once(__DIR__ . '/databases.php');

function get_student_profile_from_reg_no($reg_no)
{
  $student = [];

  $db = get_db();

  $resource = $db->query(
    "select first_name, surname, other_names from freshman_profile
     WHERE personalno = '$reg_no' ;"
  );

  if ($resource->rowCount()) {
    $data = $resource->fetch();

    $first_name = $data['first_name'];
    $other_names = $data['other_names'];
    $surname = $data['surname'];
    $names = '';

    if ($first_name) {
      $names .= $first_name . ' ';
    }

    $student['names'] = sprintf('%s %s %s', $names, $surname, $other_names);
  }

  return $student;

}
