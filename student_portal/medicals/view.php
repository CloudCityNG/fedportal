<!doctype html>
<html class="no-js" lang="">
<head>
  <?php include(__DIR__ . '/../../includes/header.php'); ?>
  <link rel="stylesheet" href="<?php echo STATIC_ROOT . 'libs/css/main.min.css' ?>">
  <link rel="stylesheet"
        href="<?php echo STATIC_ROOT . 'student_portal/home/css/centralize.min.css' ?>"/>
</head>

<body>
<div class="app">
  <?php include(__DIR__ . '/../includes/nav.php') ?>

  <section class="layout">

    <!-- main content -->
    <section class="main-content">

      <!-- content wrapper -->
      <div class="content-wrap">

        <!-- inner content wrapper -->
        <div class="wrapper">
          <section class="panel">
            <div class="panel-body">
              <form role="form" method="post" class="well"
                    data-fv-framework="bootstrap"
                    data-fv-message="This value is not valid"
                    data-fv-icon-valid="glyphicon glyphicon-ok"
                    data-fv-icon-invalid="glyphicon glyphicon-remove"
                    data-fv-icon-validating="glyphicon glyphicon-refresh">

                <input type="hidden" name="medicals_input[reg_no]" value="<?php echo $regNo; ?>"/>

                <fieldset class="medicals-field-set">
                  <legend class="text-center">Medicals</legend>

                  <div class="form-group">
                    <label class="control-label" for="blood_group">Blood Group</label>

                    <select class="form-control" name="medicals_input[blood_group]" id="blood_group" required>
                      <option value="">--</option>
                      <option value="A">A</option>
                      <option value="B">B</option>
                      <option value="AB">AB</option>
                      <option value="O">O</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="genotype">Genotype</label>

                    <select class="form-control" name="medicals_input[genotype]" id="genotype" required>
                      <option value="">--</option>
                      <option value="AA">AA</option>
                      <option value="AS">AS</option>
                      <option value="SS">SS</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="allergies">
                      Do you have any allergy? Please state separated by commas
                    </label>
                    <input class="form-control" type="text" name="medicals_input[allergies]" id="allergies"
                           placeholder="Chloroquine Injections, allergy b, allergy c"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="medical_conditions">
                      If you have suffered from any notable medical condition(s). Please state (separated by comma)
                    </label>
                    <input class="form-control" type="text" name="medicals_input[medical_conditions]"
                           id="medical_conditions"
                           placeholder="Heart Attack, Asthma, Diabetes, Ulcer"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="doctor_name">
                      Your Doctor's Name
                    </label>
                    <input class="form-control" type="text" name="medicals_input[doctor_name]"
                           id="doctor_name"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="doctor_mobile_phone">
                      Doctor's Mobile Phone
                    </label>
                    <input class="form-control" type="text" name="medicals_input[doctor_mobile_phone]"
                           id="doctor_mobile_phone"/>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="doctor_address">
                      Doctor's Address
                    </label>
                    <textarea class="form-control" name="medicals_input[doctor_address]"
                              id="doctor_address" rows="4"></textarea>
                  </div>
                </fieldset>

                <div class="text-center">
                  <button type="submit" class="btn btn-lg btn-success">Submit</button>
                </div>
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

<?php include(__DIR__ . '/../../includes/js-footer.php'); ?>

<script src="<?php echo STATIC_ROOT . 'student_portal/medicals/js/medicals.js' ?>"></script>
</body>
</html>
