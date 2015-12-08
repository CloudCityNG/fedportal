<div class="">
  <form role="form" method="post" class="well" id="bio-data-form"
        enctype="multipart/form-data"
        data-fv-framework="bootstrap"
        data-fv-message="This value is not valid"
        data-fv-icon-valid="glyphicon glyphicon-ok"
        data-fv-icon-invalid="glyphicon glyphicon-remove"
        data-fv-icon-validating="glyphicon glyphicon-refresh">

    <input name="student_bio[personalno]" type="hidden" value="<?php echo $regNo; ?>">

    <fieldset>
      <legend class="text-center">Bio Data</legend>
      <?php include(__DIR__ . '/bio-data-form.php') ?>
    </fieldset>

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
  </form>
</div>
