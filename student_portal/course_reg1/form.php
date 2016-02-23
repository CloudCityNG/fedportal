<?php
$regNo = $studentCourseRegViewContext['reg_no'];
$dept_name = $studentCourseRegViewContext['dept_name'];
$dept_code = $studentCourseRegViewContext['dept_code'];
$currentLevel = $studentCourseRegViewContext['current-level'];
?>

<div class="register-courses-container">
  <form class="courses-tables-form form-horizontal" id="courses-tables-form" method="post" action=""
        data-toggle="validator">

    <div style="margin-bottom: 30px" class="h3 text-center">
      <p>Course Sign Up Form</p>
      <?php
      echo "<p>{$dept_name}</p>
              {$registerCoursesSemesterText}  Semester {$registerCoursesAcademicYear} session";
      ?>
    </div>

    <input type="hidden" name="reg_no" value="<?php echo $regNo; ?>"/>
    <input type="hidden" name="semester" value="<?php echo $semester; ?>"/>
    <input type="hidden" name="academic_year" value="<?php echo $registerCoursesAcademicYear; ?>"/>
    <input type="hidden" name="dept" value="<?php echo $dept_code; ?>"/>

    <div class="level">
      <div class="form-group">
        <label class="control-label col-sm-3" for="level">Select your level </label>

        <div class="col-sm-6">
          <select class="form-control" name="level" id="level" required=""
                  data-native-error="Please select your level!">
            <option value="">---</option>

            <?php
            foreach (AcademicLevels::getAllLevels() as $the_level) {
              echo "<option value='{$the_level['code']}'>{$the_level['code']}</option>";
            }
            ?>
          </select>

          <div class="with-errors help-block"></div>
        </div>
      </div>
    </div>

    <div class="courses-tables-ond" style="display: none">
      <?php require(__DIR__ . '/ond1_courses.php'); ?>
      <?php require(__DIR__ . '/ond2_courses.php'); ?>
    </div>

    <div class="courses-tables-hnd" style="display: none">
      <?php require(__DIR__ . '/hnd1_courses.php'); ?>
      <?php require(__DIR__ . '/hnd2_courses.php'); ?>
    </div>

    <div class="text-center">
      <input style="display: none;" class="btn btn-primary" type="submit" value="Sign Up For Courses"
             id="course-form-submit" disabled/>
    </div>
  </form>
</div>
