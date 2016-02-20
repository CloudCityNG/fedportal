<?php
require_once(__DIR__ . '/../login/auth.php');

include_once(__DIR__ . '/../../helpers/app_settings.php');

require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');

include_once(__DIR__ . '/../../helpers/models/Medicals.php');

class MedicalsController
{

  public function get()
  {
    $regNo = $_SESSION['REG_NO'];

    if (Medicals::get(['reg_no' => $regNo, '__exists' => true])) {

      set_student_reg_form_completion_session1(
        'error', "Your Medical data exists in database!");

      $home = STATIC_ROOT . 'student_portal/home/';
      header("Location: {$home}");

      return;

    }

    include(__DIR__ . '/view.php');
  }

  public function post()
  {
    $medicals_form_inputs = $_POST['medicals_input'];

    if (Medicals::save($medicals_form_inputs)) {


      set_student_reg_form_completion_session1(
        'success', "Medical records successfully saved!");

    } else {

      set_student_reg_form_completion_session1(
        'error', "Medical records cannot be saved!"
      );

    }

    $home = STATIC_ROOT . 'student_portal/home/';
    header("Location: {$home}");

    exit();
  }
}

$medicals = new MedicalsController;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $medicals->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $medicals->post();
}
