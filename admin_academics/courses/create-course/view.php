<?php
require(__DIR__ . '/form-post-message.php');
echo $createCoursePostMessage;

/**
 * @param bool $isEditing - whether we are editing or creating a course
 * @param null $displayedCourseData
 * @param null $originalCourseData
 * @param string $key
 * @return array - of the form [
 *    'value for form control',
 *    'value for disabled attr of form element',
 *    'icon for toggling form control'
 * ]
 */
function getEditIcon($isEditing = false, $displayedCourseData = null, $originalCourseData = null, $key = '')
{
  if (!$isEditing || !$displayedCourseData) return ['', '', '<span class="input-group-addon"></span>'];

  $edit = '';
  $view = '';
  $disabled = '';
  $displayedVal = $displayedCourseData[$key];

  if ($displayedVal == $originalCourseData[$key]) {
    $view = "display: none;";
    $disabled = 'disabled';

  } else $edit = 'display: none;';

  $icon = "
        <span class='input-group-addon'>
          <span class='glyphicon glyphicon-pencil toggle-form-control-edit' style='cursor:pointer;{$edit}'></span>
          <span class='glyphicon glyphicon-eye-open toggle-form-control-edit' style='cursor:pointer;{$view}'></span>
        </span>
      ";

  return [$displayedVal, $disabled, $icon];
}

?>

<form role="form" method="post" class="well" id="course-create-form" novalidate
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh">

  <fieldset>
    <?php
    /**
     * If on the server it is necessary to send course data back to user (because there is need to correct data), then
     * this is stored in this variable
     * @param array $displayedCourse
     */
    $displayedCourse = isset($createCourseContext['displayed_course']) ? $createCourseContext['displayed_course'] : null;

    /**
     * @param array $unmodifiedCourse
     */
    $unmodifiedCourse = $displayedCourse;
    if (isset($createCourseContext['unmodified_course'])) {
      $unmodifiedCourse = $createCourseContext['unmodified_course'];
    }


    /**
     * This is set to true only if we are editing a course and not creating course from scratch
     * @param boolean $isEditingCourse
     */
    $isEditingCourse = isset($createCourseContext['edit']) ? $createCourseContext['edit'] : false;

    /**
     * Whether our controls start out disabled. This will always be the case when showing an existing course
     * @param string $disabled
     */
    $disabled = '';

    $legend = 'Create New Course';
    $editIcon = '<span class="input-group-addon"></span>';
    $submitBtnLabel = 'Create Course';
    $departmentMappings = $createCourseContext['department_mapping'];

    if ($isEditingCourse) {
      $submitBtnLabel = 'Edit Course';
      $disabled = 'disabled';
      $legend = 'Edit Course';

      $editIcon = '
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-pencil toggle-form-control-edit" style="cursor:pointer;"></span>
          <span class="glyphicon glyphicon-eye-open toggle-form-control-edit" style="cursor:pointer;display: none;"></span>
        </span>
      ';

      $displayedCourseJson = json_encode($unmodifiedCourse);
      $courseId = $displayedCourse['id'];
      echo "<input type='hidden' value='{$displayedCourseJson}' id='current-course-data' name='current_course_data' />";
    }

    echo "<legend>{$legend}</legend>";
    ?>

    <div class="form-group course-title-group">
      <label class="control-label" for="course-title">Course title</label>

      <div class="input-group">
        <input class="form-control check-original-course-data" type="text" name="course[title]" id="course-title"
               required value="<?php if ($displayedCourse) echo $displayedCourse['title']; ?>"
          <?php echo $disabled; ?>
               data-fv-stringlength="true" data-fv-stringlength-min="3"/>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group course-code-group">
      <label class="control-label" for="course-code">Course code</label>

      <div class="input-group">
        <?php
        list($codeV, $codeD, $codeT) = getEditIcon($isEditingCourse, $displayedCourse, $unmodifiedCourse, 'code');
        ?>

        <input class="form-control check-original-course-data" type="text" name="course[code]"
               id="course-code" required value="<?php echo $codeV; ?>" <?php echo $codeD; ?>
               pattern="^[A-Za-z]{3}\.\d{3}"
               data-fv-regexp="true" data-fv-message="Please enter string such as GNS.102"
        />

        <?php echo $codeT; ?>
      </div>
    </div>

    <div class="form-group course-unit-group">
      <label class="control-label" for="course-unit">Unit</label>

      <div class="input-group">
        <?php
        list($unitV, $unitD, $unitT) = getEditIcon($isEditingCourse, $displayedCourse, $unmodifiedCourse, 'unit');
        ?>

        <input class="form-control check-original-course-data" type="text" name=" course[unit]"
               id="course-unit" required pattern="^\d{1,2}(?:\.\d{0,2})?$"
               value="<?php echo $unitV; ?>" <?php echo $unitD; ?>
               data-fv-regexp="true" data-fv-message="Please enter number such as 2 or 2.0"
        />

        <?php echo $unitT; ?>
      </div>
    </div>

    <div class="form-group department-group">
      <label class="control-label" for="course-department">Department</label>

      <div class="input-group">
        <?php
        list($deptV, $deptD, $deptT) = getEditIcon($isEditingCourse, $displayedCourse, $unmodifiedCourse, 'department');
        ?>

        <select class="form-control check-original-course-data" name=" course[department]"
                id="course-department" required <?php echo $deptD; ?>
        >
          <option value="">----</option>

          <?php
          foreach ($departmentMappings as $deptCode => $description) {
            $deptSelected = $displayedCourse && $displayedCourse['department'] == $deptCode ? 'selected' : '';
            echo "<option value='{$deptCode}' {$deptSelected}>$description</option>";
          }
          ?>
        </select>

        <?php echo $deptT; ?>
      </div>
    </div>

    <div class="form-group course-level-group">
      <label class="control-label" for="course-level">Level</label>

      <div class="input-group">
        <?php
        list($levelV, $levelD, $levelT) = getEditIcon($isEditingCourse, $displayedCourse, $unmodifiedCourse, 'class');
        ?>

        <select class="form-control check-original-course-data" name=" course[class]" id="course-level"
                required <?php echo $levelD; ?> >

          <option value="">----</option>

          <?php
          foreach ($createCourseContext['levels_mapping'] as $code => $description) {
            $levelSelected = $displayedCourse && $displayedCourse['class'] === $code ? 'selected' : '';
            echo "<option value='{$code}' {$levelSelected}>{$description}</option>";
          }
          ?>
        </select>

        <?php echo $levelT; ?>
      </div>
    </div>

    <div class="form-group course-semester-group">
      <label class="control-label" for="course-semester">Semester</label>

      <div class="input-group">

        <?php
        list($semV, $semD, $semT) = getEditIcon($isEditingCourse, $displayedCourse, $unmodifiedCourse, 'semester');
        ?>

        <select class="form-control check-original-course-data" name=" course[semester]"
                id="course-semester" required <?php echo $semD; ?> >

          <option value="">----</option>
          <option value="1"
            <?php if ($displayedCourse && $displayedCourse['semester'] == 1) echo 'selected'; ?> >1st semester
          </option>

          <option value="2"
            <?php if ($displayedCourse && $displayedCourse['semester'] == 2) echo 'selected'; ?>>2nd semester
          </option>
        </select>

        <?php echo $semT; ?>
      </div>
    </div>

    <div class="form-group course-is-active-group">
      <label class="control-label" for="course-active">Active</label>

      <div class="input-group">
        <?php
        list($actV, $actD, $actT) = getEditIcon($isEditingCourse, $displayedCourse, $unmodifiedCourse, 'active');
        ?>

        <select class="form-control check-original-course-data" name=" course[active]"
                id="course-active" required <?php echo $actD; ?> >

          <option value="1"
            <?php if ($displayedCourse && $displayedCourse['active'] == 1) echo 'selected'; ?> >Active
          </option>

          <option value="0"
            <?php if ($displayedCourse && $displayedCourse['active'] == 0) echo 'selected'; ?>>Inactive
          </option>
        </select>

        <?php echo $actT; ?>
      </div>
    </div>

  </fieldset>

  <div class="text-center" style="margin-top: 30px;">
    <button class="btn" type="submit" name="submit-btn" id="submit-btn" <?php echo $disabled; ?>>
      <?php echo $submitBtnLabel; ?>
    </button>
  </div>
</form>
