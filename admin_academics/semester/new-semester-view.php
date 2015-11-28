<div>
  <?php
  if (!AcademicSession::getCurrentSession()) {
    echo 'New session has not been set. New semester is not available';

  } else {
    require __DIR__ . '/new-semester-form.php';
  }
  ?>
</div>
