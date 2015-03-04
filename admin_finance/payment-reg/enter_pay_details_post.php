<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 12-Feb-15
 * Time: 1:29 PM
 */
include_once(__DIR__ . '/../../helpers/models/StudentPayment.php');
include_once(__DIR__ . '/../../helpers/app_settings.php');

$payment = new StudentPayment();

$details = $payment->save_payment($_POST['student_payment']);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$_SESSION['pay-details-received-from-student-success'] = json_encode($details);

session_write_close();

$redirect = STATIC_ROOT . 'admin_finance/payment-reg';

header("Location: {$redirect}");
