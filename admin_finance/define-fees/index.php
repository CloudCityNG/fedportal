<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');
require_once(__DIR__ . '/../../admin_academics/models/AcademicLevels.php');
include_once(__DIR__ . '/../../admin_academics/models/AcademicDepartment.php');


$academic_levels = [];

foreach (AcademicLevels::get_all_levels() as $academic_level) {
  $academic_levels[$academic_level['id']] = $academic_level['code'];
}


$departments = [];

foreach (AcademicDepartment::getAcademicDepartments() as $entry) {
  $departments[$entry['code']] = $entry['description'];
}

$fees_info = '';

if (isset($_SESSION['fees-info'])) {
  $fees_info = $_SESSION['fees-info'];

  if (!empty($fees_info)) {
    $fees_array = json_decode($fees_info);

    if (isset($fees_array->error)) {
      $success_msg = '<div class="alert alert-danger alert-dismissible">' .

        ' <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                       </button>' .

        $fees_array->error .

        '</div>';

    } else {

      $academic_years = $fees_array->academic_year;

      $academic_level = $fees_array->level;

      $dept = $departments[$fees_array->dept_code];

      $success_msg = '<div class="alert alert-success">Fees successfully set for: ' .

        '   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>' .

        $academic_years . '  |  ' . $academic_level . '  |  ' . $dept .
        '</div>';
    }

    unset($_SESSION['fees-info']);
  }
}

include(__DIR__ . '/define-fees-view.php');
