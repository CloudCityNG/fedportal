<div class="">
  <form class="well" method="post"
        data-fv-framework="bootstrap"
        data-fv-message="This value is not valid"
        data-fv-icon-valid="glyphicon glyphicon-ok"
        data-fv-icon-invalid="glyphicon glyphicon-remove"
        data-fv-icon-validating="glyphicon glyphicon-refresh">
    <input type="hidden" name="reg_no" value="<?php echo $_SESSION['REG_NO']; ?>"/>

    <?php include(__DIR__ . '/primary-edu.html'); ?>

    <hr/>

    <?php include(__DIR__ . '/secondary-edu.html'); ?>

    <hr/>

    <?php include(__DIR__ . '/post-secondary.html'); ?>

    <hr/>

    <?php include(__DIR__ . '/o-levels.html'); ?>

    <div class="text-center">
      <input class="btn btn-primary btn-lg" type="submit" value="Submit"/>
    </div>
  </form>
</div>
