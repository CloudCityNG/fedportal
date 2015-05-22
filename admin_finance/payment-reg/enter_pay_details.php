<?php

include_once(__DIR__ . '/../../helpers/app_settings.php');

$redirect = STATIC_ROOT . 'admin_finance/payment-reg';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  header("Location: {$redirect}");
}

require_once(__DIR__ . '/../../helpers/models/StudentProfile.php');

$reg_no = $_POST['reg_no'];

if (!StudentProfile::student_exists($reg_no)) {
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $_SESSION['RECEIVE-PAYMENT-INVALID-STUDENT'] = $reg_no;

  session_write_close();

  header("Location: {$redirect}");
}

include_once(__DIR__ . '/ReceivePaymentFromStudent.php');

$receive_payment = new ReceivePaymentFromStudent($_POST);
?>

<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include_once(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo STATIC_ROOT . 'libs/css/main.min.css' ?>">
  <link rel="stylesheet"
        href="<?php echo STATIC_ROOT . 'admin_finance/payment-reg/css/enter-pay-details.css' ?>"/>
</head>

<body>
<div class="app horizontal-layout">
  <?php include_once(__DIR__ . '/../includes/admin-finance-nav.php'); ?>

  <section class="layout">

    <!-- main content -->
    <section class="main-content">

      <!-- content wrapper -->
      <div class="content-wrap">

        <!-- inner content wrapper -->
        <div class="wrapper">
          <section class="panel">
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-8">
                  <div class="pay-details">
                    <table class="img-and-name">
                      <tbody>
                      <tr>
                        <td>
                          <img src="<?php echo $receive_payment->student->photo ?>"
                               alt="<?php echo $receive_payment->student->names ?>"/>
                        </td>

                        <td class="names">
                          <?php
                          $student = $receive_payment->student;

                          $currents = $receive_payment->student->getCurrentLevelDept();

                          $dept_name = $currents['dept_name'];

                          $dept_code = $currents['dept_code'];

                          $level = $currents['level'];
                          ?>

                          <div>
                            <div><strong>NAMES:</strong> <?php echo strtoupper($student->names); ?></div>

                            <div><strong>MATRIC NO:</strong> <?php echo $receive_payment->reg_no ?> </div>

                            <div><strong>DEPARTMENT:</strong> <?php echo $dept_name; ?></div>

                            <div><strong>LEVEL:</strong> <?php echo $level; ?></div>

                            <div>
                              <strong>TOTAL OWING:</strong>
                              <?php echo 'NGN  ' . number_format($student->get_owing(), 2); ?>

                              <span id="amount-owing"
                                    style="display: none"><?php echo $student->get_owing(); ?></span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      </tbody>
                    </table>

                    <form action="enter_pay_details_post.php" method="post"
                          class="payment-reg-enter-pay-details-form form-horizontal">

                      <input type="hidden" name="student_payment[reg_no]"
                             value="<?php echo $receive_payment->reg_no; ?>"/>

                      <input type="hidden" name="student_payment[dept_code]"
                             value="<?php echo $dept_code; ?>"/>

                      <input type="hidden" name="student_payment[level]"
                             value="<?php echo $level; ?>"/>

                      <fieldset>
                        <legend>Enter Details of Payment Received From Student</legend>

                        <div class="form-group">
                          <label class="control-label col-sm-3" for="amount-text">
                            Amount Received
                          </label>

                          <div class="col-sm-9">
                            <div class="input-group">
                              <span class="input-group-addon">NGN</span>

                              <input class="form-control" id="amount-text" name="amount-text"
                                     required maxlength="10"/>

                              <input type="hidden" name="student_payment[amount]" id="amount"/>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-sm-3" for="receipt-no">Receipt Number</label>

                          <div class="col-sm-9">
                            <div class="input-group">
                              <span class="input-group-addon">#</span>

                              <input class="form-control" name="student_payment[receipt_no]"
                                     id="receipt-no" required data-minlength="3"/>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-sm-3" for="remark">Remark</label>

                          <div class="col-sm-9">
                            <?php $default_remark = "Payment received for $receive_payment->academic_year session, $level." ?>

                            <textarea class="form-control" name="student_payment[remark]"
                                      id="remark"><?php echo $default_remark ?></textarea>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-sm-3" for="academic_year">Academic Year</label>

                          <div class="col-sm-9">
                            <select class="form-control" required
                                    name="student_payment[academic_year]" id="academic_year">
                              <option value="<?php echo $receive_payment->academic_year ?>">
                                <?php echo $receive_payment->academic_year ?>
                              </option>

                              <?php
                              foreach ($receive_payment->past_academic_years as $past_year) {
                                echo "<option value='$past_year'>$past_year</option>";
                              }
                              ?>
                            </select>
                          </div>
                        </div>
                      </fieldset>

                      <div class="text-center">
                        <button class="btn btn-lg btn-success" type="submit"
                                name="process-pay-details-received-from-student-submit"
                                id="process-pay-details-received-from-student-submit">
                          Register Payment
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <!-- /inner content wrapper -->

      </div>
      <!-- /content wrapper -->
      <a class="exit-offscreen"></a>
    </section>
    <!-- /main content -->
  </section>

</div>

<?php include_once(__DIR__ . '/../../includes/js-footer.php') ?>

<script
  src="<?php echo STATIC_ROOT . 'admin_finance/payment-reg/js/enter-pay-details.js' ?>"></script>
</body>
</html>
