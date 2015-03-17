<!doctype html>
<html lang="en" class="no-js">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo STATIC_ROOT . 'libs/css/main.min.css' ?>">
  <style>
    .panel-body {
      margin-left: 10px;
      margin-top: 110px;
    }

    form {
      max-width: 450px;
    }
  </style>
</head>

<body>
<div class="app horizontal-layout">
  <?php include(__DIR__ . '/../includes/admin-finance-nav.php'); ?>

  <section class="layout">
    <section class="main-content">
      <div class="content-wrap">
        <div class="wrapper">
          <section class="panel">
            <header class="panel-heading no-b"></header>

            <div class="panel-body">
              <?php echo isset($success_msg) ? $success_msg : '' ?>
              <div class="row">
                <div class="col-sm-4">
                  <form class="well" action="define-fees-post.php" role="form" method="post"
                        id="define-fees-form">
                    <div class="form-group">
                      <label class="control-label" for="academic_year">Academic Year</label>

                      <select class="form-control" name="academic_year" id="academic_year" required>
                        <option value="">-------</option>
                        <?php
                        foreach (AcademicSession::get_two_most_recent_sessions() as $academic_sessions) {
                          echo "<option value='{$academic_sessions['session']}'>
                                  {$academic_sessions['session']}
                                </option>";
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="control-label" for="department">Department</label>

                      <select class="form-control" name="department" id="department" required>
                        <option value="">-------</option>

                        <?php
                        foreach ($departments as $dept_code => $desc) {
                          echo "<option value='{$dept_code}'>{$desc}</option>";
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="control-label" for="level">Level</label>

                      <select class="form-control" name="level" id="level" required>
                        <option value="">----</option>

                        <?php
                        foreach ($academic_levels as $id => $code) {
                          echo "<option value='{$code}'>{$code}</option>";
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="control-label" for="fee-text">School Fees total</label>

                      <input class="form-control" type="text" name="fee-text" id="fee-text" required/>
                      <input type="hidden" name="fee" id="fee" required/>
                    </div>

                    <div class="form-group text-center">
                      <input class="btn btn-success btn-lg" type="submit" value="Submit"/>
                    </div>
                  </form>
                </div>

                <div class="col-sm-8">
                  <h3 class="text-center text-primary">RECENTLY ADDED FEES</h3>

                  <table class="table table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Academic Year</th>
                      <th>Level</th>
                      <th>Department</th>
                      <th>Amount Set (NGN)</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $db = get_db();
                    $stmt = $db->query(
                      "SELECT academic_year, academic_level, department, fee FROM school_fees
                         ORDER BY id DESC LIMIT 6;"
                    );

                    if ($stmt->rowCount()) {
                      $fees_counter = 0;

                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ++$fees_counter;

                        $session_for_table = $row['academic_year'];

                        $level_for_table = $row['academic_level'];

                        $dept_for_table = $departments[$row['department']];

                        $fee_for_table = number_format($row['fee'], 2);

                        echo "<tr>
                                <td>{$fees_counter}</td>\n
                                <td>{$session_for_table}</td>\n
                                <td>{$level_for_table}</td>\n
                                <td>{$dept_for_table}</td>\n
                                <td align='right'>{$fee_for_table}</td>\n
                              </tr>";
                      }
                    }
                    ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
  </section>
</div>

<?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . 'admin_finance/define-fees/js/define-fees.js' ?>"></script>
</body>
</html>
