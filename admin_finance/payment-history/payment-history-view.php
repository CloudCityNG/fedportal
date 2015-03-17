<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo STATIC_ROOT . 'libs/css/main.min.css' ?>">
  <link rel="stylesheet"
        href="<?php echo STATIC_ROOT . '/admin_finance/payment-history/css/payment-history-view.css'; ?>"/>
</head>

<body>
<div class="app horizontal-layout">
  <?php include(__DIR__ . '/../includes/admin-finance-nav.php'); ?>

  <section class="layout">

    <!-- main content -->
    <section class="main-content">

      <!-- content wrapper -->
      <div class="content-wrap">

        <!-- inner content wrapper -->
        <div class="wrapper">
          <section class="panel">
            <div class="panel-body">
              <form role="form" method="post" class="form-inline">
                <div class="form-group reg-no-form-group">
                  <label for="reg_no">Student Registration Number </label>

                  <input class="form-control" name="reg_no" id="reg_no" required data-minlength="5"
                         data-native-error="Invalid registration number"/>

                  <button class="btn btn-primary" type="submit" name="student-payment-history-button">
                    Get Payment History
                  </button>

                  <span class="with-errors help-block"></span>
                </div>
              </form>

              <div class="payment-history-data-container" style="display: none"></div>
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

<?php include_once(__DIR__ . '/../../includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . 'admin_finance/payment-history/js/payment-history.js' ?>"></script>
</body>
</html>
