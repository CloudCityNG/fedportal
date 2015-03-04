<?php
$pay_reg_success_details = $_SESSION['pay-details-received-from-student-success'];

$std = json_decode($pay_reg_success_details);

$std->amount = number_format($std->amount, 2);

$display_alert = 'block';

unset($_SESSION['pay-details-received-from-student-success']);
?>

<div class="alert alert-success alert-dismissible' role='alert"
     style="max-width: 650px; margin: 150px auto;">

  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>

  <h5>At <?php echo $std->created_at ?>, the following payment was successfully registered</h5>

  <table class='table table-bordered table-condensed table-striped'>
    <tbody>
      <tr>
        <th>Amount Paid</th>
        <td>NGN <?php echo $std->amount ?></td>
      </tr>

      <tr>
        <th>Payment ID</th>
        <td>
          <?php echo $std->id ?>
        </td>
      </tr>

      <tr>
        <th>Matriculation Number</th>
        <td>
          <?php echo $std->reg_no ?>
        </td>
      </tr>

      <tr>
        <th>Academic Year</th>
        <td>
          <?php $std->academic_year ?>
        </td>
      </tr>

      <tr>
        <th>Level</th>
        <td>
          <?php echo $std->level ?>
        </td>
      </tr>

      <tr>
        <th>Department</th>
        <td>
          <?php echo $std->dept_code ?>
        </td>
      </tr>

      <tr>
        <th>Receipt No.</th>
        <td>
          <?php echo $std->receipt_no ?>
        </td>
      </tr>

      <tr>
        <th>Remark</th>
        <td>
          <?php echo $std->remark ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>