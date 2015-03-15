<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 31-Jan-15
 * Time: 7:52 PM
 */

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include_once(__DIR__ . '/../../helpers/app_settings.php');

include_once(__DIR__ . '/../../helpers/databases.php');

require_once(__DIR__ . '/../../admin_academics/models/AcademicSession.php');

include_once(__DIR__ . '/../../helpers/get_academic_levels.php');

include_once(__DIR__ . '/../../helpers/get_academic_departments.php');


$academic_levels = get_academic_levels();

$departments = get_academic_departments();

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
