<?php
require_once(__DIR__ . '/../login/auth.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  include_once(__DIR__ . '/../../helpers/app_settings.php');

  include(__DIR__ . '/payment-history-view.php');

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  include_once(__DIR__ . '/../../helpers/models/StudentProfile.php');

  $regNo = $_POST['reg_no'];

  $returned_val = [
    'found' => false,

    'regNo' => $regNo
  ];

  if (StudentProfile::student_exists($regNo)) {

    $returned_val['found'] = true;

    $profile = new StudentProfile($regNo);

    $returned_val['billsHistory'] = $profile->get_billing_history();
    $returned_val['student'] = $profile->getCompleteCurrentDetails();

  }

  header("Content-type: application/json");

  echo json_encode($returned_val);

}
