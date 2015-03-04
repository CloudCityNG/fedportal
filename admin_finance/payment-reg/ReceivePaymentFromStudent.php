<?php
/**
 * User: maneptha Date: 11-Feb-15
 */

include_once(__DIR__ . '/../../helpers/models/StudentProfile.php');

include_once(__DIR__ . '/../../helpers/models/AcademicYear.php');

include_once(__DIR__ . '/../../helpers/app_settings.php');


class ReceivePaymentFromStudent
{

  public $reg_no;

  public $student;

  public $academic_year;

  public $past_academic_years;

  function __construct($post)
  {

    $this->reg_no = $post['reg_no'];

    $this->student = new StudentProfile($this->reg_no);

    if (!$this->student->names) {
      $this->set_student_not_found_session();

      return;
    }

    $academic_years = new AcademicYear();

    $eight_academic_years = $academic_years->get_years(8);

    $this->academic_year = $eight_academic_years[0];

    $this->past_academic_years = array_slice($eight_academic_years, 1);

  }

  private function set_student_not_found_session()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION['register-payment-student-not-found'] = "Invalid student registration number $this->reg_no";

    $payment_reg_url = STATIC_ROOT . 'admin_finance/payment_reg.php';

    header("Location: $payment_reg_url");
  }
}
