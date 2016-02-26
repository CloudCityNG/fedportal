<?php
require_once(__DIR__ . '/../login/auth.php');
include_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../home/set_student_reg_form_completion_session.php');
include_once(__DIR__ . '/../../helpers/models/Medicals.php');

class MedicalsController1
{

  public function get()
  {
    $link_template = __DIR__ . '/view.php';
    $pageJsPath = path_to_link(__DIR__ . '/js/medicals.min.js', true);
    require(__DIR__ . '/../home/container.php');
  }

  public function post()
  {
    $medicals_form_inputs = $_POST['medicals_input'];

    if (Medicals::save($medicals_form_inputs)) {


      set_student_reg_form_completion_session1('success', "Medical records successfully saved!");

    } else set_student_reg_form_completion_session1('error', "Medical records cannot be saved!");

    $home = STATIC_ROOT . 'student_portal/home1/';
    header("Location: {$home}");
  }
}

$medicals = new MedicalsController1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  $medicals->get();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $medicals->post();
}
