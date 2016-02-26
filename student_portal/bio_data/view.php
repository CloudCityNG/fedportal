<div class="">
  <form role="form" class="well"
    <?php
    if (!$student) {
      echo ' id="bio-data-form" method="post"
                enctype="multipart/form-data"
                data-fv-framework="bootstrap"
                data-fv-message="This value is not valid"
                data-fv-icon-valid="glyphicon glyphicon-ok"
                data-fv-icon-invalid="glyphicon glyphicon-remove"
                data-fv-icon-validating="glyphicon glyphicon-refresh"
            ';
    }
    ?>
  >

    <?php
    if (!$student) {
      echo "<input name='student_bio[personalno]' type='hidden' value='{$regNo}'>";
    }
    ?>

    <fieldset>
      <legend class="text-center">Bio Data <?php if ($student) echo ' Completed'; ?> </legend>
      <?php require(__DIR__ . '/bio-data-form.php') ?>
    </fieldset>

    <?php
    if (!$student) {
      echo '
          <fieldset>
            <legend class="text-center">Upload Your Photo</legend>

            <div class="form-group">
              <input class="form-control" type="file" id="photo" name="photo" required
                     data-fv-file="true"
                     data-fv-file-type="image/jpeg,image/png,image/gif,image/png,image/tiff"
                     data-fv-file-maxsize="51200"/>
              <span>*** Please ensure that your photo does not exceed 50kb in size.</span>
            </div>
          </fieldset>

          <div class="text-center">
            <button class="btn btn-primary btn-lg" type="submit">Register</button>
          </div>
        ';
    }
    ?>
  </form>
</div>
