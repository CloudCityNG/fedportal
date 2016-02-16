<?php
require(__DIR__ . '/form-post-message.php');
echo $createCoursePostMessage;
?>

<form role="form" method="post" class="well" id="course-create-form" novalidate
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh">

  <fieldset>
    <?php
    $currentCourse = isset($createCourseContext['current_course']) ? $createCourseContext['current_course'] : null;
    $editCourse = isset($createCourseContext['edit']) ? $createCourseContext['edit'] : null;
    $disabled = '';
    $legend = 'Create New Course';
    $editIcon = '<span class="input-group-addon"></span>';
    $submitBtnLabel = 'Create Course';
    $departmentMappings = $createCourseContext['department_mapping'];

    if ($editCourse) {
      $submitBtnLabel = 'Edit Course';
      $disabled = 'disabled';
      $legend = 'Edit Course';

      $editIcon = '
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-pencil toggle-form-control-edit" style="cursor:pointer;"></span>
          <span class="glyphicon glyphicon-eye-open toggle-form-control-edit" style="cursor:pointer;display: none;"></span>
        </span>
      ';

      $currentCourseJson = json_encode($currentCourse);
      echo "<input type='hidden' value='{$currentCourseJson}' id='current-course-data' name='current_course_data' />";
    }

    echo "<legend>{$legend}</legend>";
    ?>

    <div class="form-group username-group">
      <label class="control-label" for="course-title">Course title</label>

      <div class="input-group">
        <input class="form-control check-original-course-data" type="text" name="course[title]" id="course-title"
               required value="<?php if ($currentCourse) echo $currentCourse['title']; ?>" <?php echo $disabled; ?>
               data-fv-stringlength="true" data-fv-stringlength-min="3"/>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group first-name-group">
      <label class="control-label" for="course-code">Course code</label>

      <div class="input-group">
        <input class="form-control check-original-course-data" type="text" name="course[code]"
               id="course-code" required
               value="<?php if ($currentCourse) echo $currentCourse['code']; ?>" <?php echo $disabled; ?>
               pattern="^[A-Za-z]{3}\.\d{3}"
               data-fv-regexp="true" data-fv-message="Please enter string such as GNS.102"
        />

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group last-name-group">
      <label class="control-label" for="course-unit">Unit</label>

      <div class="input-group">
        <input class="form-control check-original-course-data" type="text" name=" course[unit]"
               id="course-unit" required pattern="^\d{1,2}(?:\.\d{0,2})?$"
               value="<?php if ($currentCourse) echo $currentCourse['unit']; ?>" <?php echo $disabled; ?>
               data-fv-regexp="true" data-fv-message="Please enter number such as 2 or 2.0"
        />

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group department-group">
      <label class="control-label" for="course-department">Department</label>

      <div class="input-group">
        <select class="form-control check-original-course-data" name=" course[department]" id="course-department"
                required
          <?php echo $disabled; ?>
        >
          <option value="">----</option>

          <?php
          foreach ($departmentMappings as $deptCode => $description) {
            $deptSelected = $currentCourse && $currentCourse['department'] == $deptCode ? 'selected' : '';
            echo "<option value='{$deptCode}' {$deptSelected}>$description</option>";
          }
          ?>
        </select>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group level-group">
      <label class="control-label" for="course-level">Level</label>

      <div class="input-group">
        <select class="form-control check-original-course-data" name=" course[class]" id="course-level" required
          <?php echo $disabled; ?>
        >
          <option value="">----</option>

          <?php
          foreach ($createCourseContext['levels_mapping'] as $code => $description) {
            $levelSelected = $currentCourse && $currentCourse['class'] === $code ? 'selected' : '';
            echo "<option value='{$code}' {$levelSelected}>{$description}</option>";
          }
          ?>
        </select>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group semester-group">
      <label class="control-label" for="course-semester">Semester</label>

      <div class="input-group">
        <select class="form-control check-original-course-data" name=" course[semester]" id="course-semester" required
          <?php echo $disabled; ?>
        >
          <option value="">----</option>
          <option value="1"
            <?php if ($currentCourse && $currentCourse['semester'] == 1) echo 'selected'; ?> >1st semester
          </option>

          <option value="2"
            <?php if ($currentCourse && $currentCourse['semester'] == 2) echo 'selected'; ?>>2nd semester
          </option>
        </select>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group course-is-active-group">
      <label class="control-label" for="course-active">Active</label>

      <?php
      $checkActive = !$editCourse || ($currentCourse && $currentCourse['active']) ? 'checked' : '';
      ?>
      <input style="display: inline-block; margin-left: 10px; margin-right: 10px;"
             class="check-original-course-data" type="checkbox" name="course[active]"
             id="course-active" <?php echo $disabled . ' ' . $checkActive; ?> />

      <?php
      if ($editCourse) {
        echo '
            <span class="">
              <span class="glyphicon glyphicon-pencil toggle-form-control-edit" style="cursor:pointer;"></span>
              <span class="glyphicon glyphicon-eye-open toggle-form-control-edit" style="cursor:pointer;display: none;"></span>
            </span>';
      }
      ?>
    </div>

  </fieldset>

  <div class="text-center" style="margin-top: 30px;">
    <button class="btn" type="submit" name="submit-btn" id="submit-btn" <?php echo $disabled; ?>>
      <?php echo $submitBtnLabel; ?>
    </button>
  </div>
</form>
