<?php
/**
 * Created by maneptha on 15-Feb-15.
 */
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$message = $_SESSION['register-payment-student-not-found'];

unset($_SESSION['register-payment-student-not-found']);
?>

<div class="h4 alert alert-danger alert-dismissible' role='alert"
     style="max-width: 650px; margin: 150px auto;">

  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>

  <?php echo $message; ?>
</div>
