<?php
require_once(__DIR__ . '/../login/auth.php');
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
  <?php include_once(__DIR__ . '/../../includes/header.php'); ?>

  <style>
    .panel-body {
      margin-left: 15px;
    }

    .row {
      margin-top: 110px;
    }

    form {
      max-width: 450px;
    }
  </style>
</head>

<body>
<div class="app horizontal-layout">
  <?php include_once(__DIR__ . '/../includes/admin-finance-nav.php'); ?>

  <section class="layout">
    <section class="main-content">
      <div class="content-wrap">
        <div class="wrapper">
          <section class="panel">
            <header class="panel-heading no-b"></header>

            <div class="panel-body">
              <table class="table table-condensed table-striped table-bordered">
                <caption class="text-center">Debtor's list</caption>

                <thead>
                  <tr>
                    <td>#</td>
                    <td>Names</td>
                    <td>Registration Number</td>
                    <td>Level</td>
                    <td>Amount owing (NGN)</td>
                  </tr>
                </thead>

                <tbody>
                  <?php include_once(__DIR__ . '/../../helpers/models/StudentBilling.php');
                  $student_bills = new StudentBilling();

                  $count = 0;

                  foreach ($student_bills->get_debtors() as $debtor) {

                    ++$count;

                    $owing = number_format($debtor->owing, 2);

                    $currents = $debtor->get_current_level_dept();

                    $level = $currents['level'];



                    echo "<tr>
                            <td>$count</td>

                            <td>$debtor->names</td>

                            <td>$debtor->reg_no</td>

                            <td>$level</td>

                            <td>$owing</td>
                          </tr>";
                  }

                  ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>
      </div>

      <a class="exit-offscreen"></a>
    </section>
  </section>
</div>

<?php include_once(__DIR__ . '/../../includes/js-footer.php'); ?>

</body>
</html>