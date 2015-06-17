<form class="courses-tables-form form-horizontal" method="post" action="" data-toggle="validator">

  <header class="panel-heading no-b">
    <h3 class="text-center">
      Course <b>Sign Up Form:
        <?php echo "{$dept_name} ({$semester}" . ($semester == 1 ? 'st' : 'nd') . " Semester {$academicYear} session)"; ?>
      </b>
    </h3>
  </header>

  <input type="hidden" name="reg_no" value="<?php echo $regNo; ?>"/>
  <input type="hidden" name="semester" value="<?php echo $semester; ?>"/>
  <input type="hidden" name="academic_year"
         value="<?php echo $academicYear; ?>"/>
  <input type="hidden" name="dept" value="<?php echo $dept_code; ?>"/>

  <div class="row level">
    <div class="col-sm-10">
      <div class="form-group">
        <label class="control-label col-sm-6" for="level">Level </label>

        <div class="col-sm-4">
          <select class="form-control" name="level" id="level" required=""
                  data-native-error="Please select your level!">
            <option value="">---</option>

            <?php
            foreach (AcademicLevels::getAllLevels() as $the_level) {
              echo "<option value='{$the_level['code']}'>{$the_level['code']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="with-errors help-block"></div>

      </div>
    </div>
  </div>

  <div class="row courses-tables-ond">
    <?php require(__DIR__ . '/ond1_courses.php'); ?>
    <?php require(__DIR__ . '/ond2_courses.php'); ?>
  </div>

  <div class="row courses-tables-hnd">
    <?php require(__DIR__ . '/hnd1_courses.php'); ?>
    <?php require(__DIR__ . '/hnd2_courses.php'); ?>
  </div>

  <div class="text-center">
    <input class="btn btn-lg btn-primary" type="submit" value="Sign Up For Courses"/>
  </div>
</form>
