<?php
require_once(__DIR__ . '/helpers/auth.php');

include_once(__DIR__ . '/helpers/app_settings.php');

include_once(__DIR__ . '/helpers/set_student_reg_form_completion_session.php');

include_once(__DIR__ . '/helpers/models/Medicals.php');

class MedicalsController
{

  public function get()
  {
    $reg_no = $_SESSION['REG_NO'];

    if (Medicals::exists($reg_no)) {

      set_student_reg_form_completion_session(
        'error', "Your Medical data exists in database!");

      header("Location: student_dashboard.php");

      return;

    }

    include(__DIR__ . '/medicals_reg_view.php');
  }

  public function post()
  {
    $medicals_form_inputs = $_POST['medicals_input'];

    if (Medicals::save($medicals_form_inputs)) {


      set_student_reg_form_completion_session(
        'success', "Medical records successfully saved!");

    } else {

      set_student_reg_form_completion_session(
        'error', "Medical records cannot be saved!"
      );

    }

    header("Location: student_dashboard.php");

    exit();
  }
}

$medicals = new MedicalsController;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $medicals->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $medicals->post();
}
