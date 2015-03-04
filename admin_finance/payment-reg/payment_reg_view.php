<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$invalid_student = '';

$session_key = 'RECEIVE-PAYMENT-INVALID-STUDENT';

if (isset($_SESSION[$session_key]) && $_SESSION[$session_key]) {
  $invalid_student = "<span style='color: red'>
                         Unknown registration number: {$_SESSION[$session_key]}
                      </span>";

  unset($_SESSION[$session_key]);
}
?>

<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include_once(__DIR__ . '/../../includes/header.php'); ?>
</head>

<style>

  form {
    border: 1px solid #D8D8D8;
    padding: 15px;
    box-sizing: content-box;
    background-color: #EDEDED;
    border-radius: 5px;
  }

  .reg-no-form-group > .help-block {
    display: inline;
  }

  .reg-no-form-group > .help-block * {
    display: inline;
  }

</style>

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
              <?php
              //when payment successfully received from student, we redirect back here and display the alert

              if (isset($_SESSION['pay-details-received-from-student-success'])) {
                include(__DIR__ . '/success-alert.php');

              } elseif (isset($_SESSION['register-payment-student-not-found'])) {

                include(__DIR__ . '/student-not-found-alert.php');
              }
              ?>

              <form role="form" method="post" class="form-inline" action="enter_pay_details.php"
                    data-toggle="validator">
                <fieldset>
                  <legend>Input Student Payment</legend>

                  <div class="form-group reg-no-form-group">
                    <label for="reg_no">Student Registration Number </label>

                    <input class="form-control" name="reg_no" id="reg_no"
                           required data-minlength="5"
                           data-native-error="Invalid registration number"/>

                    <button class="btn btn-primary" type="submit"
                            name="receive-payment-get-student-details">
                      Get Student Details
                    </button>

                    <span class="with-errors help-block">
                      <?php echo $invalid_student ?>
                    </span>
                  </div>
                </fieldset>
              </form>
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
</body>
</html>