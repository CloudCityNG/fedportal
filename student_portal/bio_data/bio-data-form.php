<div class="form-group first-name-group">
  <label class="control-label" for="first-name">First Name</label>

  <input class="form-control" type="text" id="first-name"
    <?php
    if ($student) echo " disabled value='{$student['first_name']}' ";
    else {
      echo ' name="student_bio[first_name]"  required data-fv-stringlength="true" data-fv-stringlength-min="3" ';
    }
    ?>
  />
</div>

<div class="form-group surname-group">
  <label class="control-label" for="surname">Surname</label>

  <input class="form-control" type="text" id="surname"
    <?php
    if ($student) echo " disabled value='{$student['surname']}' ";
    else {
      echo ' name="student_bio[surname]" required data-fv-stringlength="true" data-fv-stringlength-min="3" ';
    }
    ?>
  />
</div>

<div class="form-group other-names-group">
  <label class="control-label" for="other_names">Other Names (If applicable)</label>

  <input class="form-control" type="text" id="other_names"
    <?php
    if ($student) echo " disabled value='{$student['other_names']}' ";
    else {
      echo ' name="student_bio[other_names]" ';
    }
    ?>
  />
</div>

<div class="form-group previous-names-group">
  <label class="control-label" for="previous-names">Previous Names (if applicable)</label>

  <input class="form-control" type="text" id="previous-names"
    <?php
    if ($student) echo " disabled value='{$student['previousname']}' ";
    else {
      echo 'name="student_bio[previousname]"';
    }
    ?>
  />
</div>

<div class="form-group email-group">
  <label class="control-label" for="email">Email Address</label>

  <input class="form-control" id="email" value="<?php echo $email; ?>"
    <?php
    if ($student) echo 'disabled';
    else {
      echo ' type="email" name="student_bio[email]" required ';
    }
    ?>
  />
</div>


<div class="form-group admission-year-group">
  <label class="control-label" for="academic_session_id">Admission Year</label>

  <?php
  if ($student) {
    echo "<input class='form-control' value='{$student['currentsession']}' disabled/>";

  } else {
    echo '
    <select name="student_bio[currentsession]" id="academic_session_id" class="form-control" required>
      <option value="">---------------</option>
    ';

    foreach (AcademicSession::getSessions(20) as $academic_session) {
      echo "\n<option value='{$academic_session['session']}'>{$academic_session['session']}</option>";
    }

    echo "\n</select>";
  }
  ?>
</div>

<div class="form-group nationality-group">
  <label class="control-label" for="nationality">Nationality</label>

  <input type="text" class="form-control" id="nationality"
    <?php
    if ($student) echo "disabled value={$student['nationality']}";
    else {
      echo ' value="Nigerian" name="student_bio[nationality]"  required ';
    }
    ?>
  />
</div>

<div class="form-group state-of-origin-group">
  <label class="control-label" for="state_of_origin">State of Origin</label>

  <?php
  if ($student) {
    echo "<input class='form-control' disabled value='{$student['state']}'/>";

  } else {
    echo '
        <select name="student_bio[state]" id="state_of_origin" class="form-control" required>
          <option value="">-------------</option>
      ';

    require(__DIR__ . '/../../includes/nigeria-states.html');

    echo "\n</select>";
  }
  ?>
</div>

<div class="form-group department-group">
  <label class="control-label" for="academic_department_id">Course of Study</label>

  <?php
  if ($student) {
    $bioDataFormCourse = $student['course'] === 'dental_technology' ? 'Dental Technology' : 'Dental Therapy';
    echo "<input class='form-control' disabled value='{$bioDataFormCourse}' />";

  } else {
    echo '
      <select name="student_bio[course]" id="academic_department_id" class="form-control" required>
        <option value="">--------------</option>
        <option value="dental_technology">Dental Technology</option>
        <option value="dental_therapy">Dental Therapy</option>
      </select>
    ';
  }
  ?>
</div>

<div class="form-group gender-group">
  <label class="control-label" for="gender">Gender</label>

  <?php
  if ($student) {
    echo "<input class='form-control' disabled value='{$student['sex']}' />";

  } else {
    echo '
        <select name="student_bio[sex]" id="gender" class="form-control" required>
          <option value="">------</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
    ';
  }
  ?>
</div>

<div class="form-group date-of-birth-group">
  <label class="control-label" for="date-of-birth-view">Date of Birth (dd-mmm-yyyy)</label>

  <div class="input-group date input-append show-date-picker">
    <input type="text" class="form-control" id="date-of-birth-view"

      <?php
      if ($student) echo "disabled value={$student['dateofbirth']}";
      else {
        echo ' maxlength="11" name="date-of-birth-view" required placeholder="dd-mmm-yyyy"
           pattern="^\d{1,2}-[A-Za-z]{3}-\d{4}$" ';
      }
      ?>
    >

    <span class="input-group-addon add-on">
    <?php if (!$student) {
      echo '<span class="glyphicon glyphicon-calendar"></span>';
    } ?>
    </span>
  </div>

  <?php if (!$student) {
    echo '
      <input type="hidden" id="date-of-birth" name="student_bio[dateofbirth]"
         data-fv-date data-fv-date-format="YYYY-MM-DD" data-fv-excluded="false"/>
    ';
  } ?>
</div>

<div class="form-group local-govt-group">
  <label for="local_govt">Local Government Area</label>

  <input type="text" id="local_govt" class="form-control"
    <?php
    if ($student) echo "disabled value={$student['lga']}";
    else {
      echo ' name="student_bio[lga]"  required ';
    }
    ?>
  />
</div>

<div class="form-group permanent-address-group">
  <label for="permanent_address" class="control-label">Permanent Address</label>

  <textarea id="permanent_address" rows="4" class="form-control"
    <?php
    if ($student) echo ' disabled ';
    else {
      echo ' name="student_bio[permanentaddress]" required ';
    }
    ?>
  ><?php if ($student) echo $student['permanentaddress']; ?></textarea>
</div>

<div class="form-group phone-group">
  <label for="mobile_phone" class="control-label">Mobile Phone</label>

  <input type="text" id="mobile_phone" class="form-control"
    <?php
    if ($student) echo "disabled value='{$student['phone']}' ";
    else {
      echo ' name="student_bio[phone]" placeholder="E.g +802345695" required pattern="\+?\d{4,}" ';
    }
    ?>
  />
</div>

<div class="form-group parent-group">
  <label class="control-label" for="guardian">Parent/Guardian</label>

  <input class="form-control" type="text" id="guardian"
    <?php
    if ($student) echo "disabled value='{$student['parentname']}' ";
    else {
      echo ' name="student_bio[parentname]"  required ';
    }
    ?>
  />
</div>

<div class="form-group emergency-contact-group">
  <label class="control-label" for="emergency_contact_address">
    Name/Phone/Address of contact person in case of emergency ( state relationship)
  </label>

  <textarea id="emergency_contact_address" rows="4" class="form-control"
    <?php
    if ($student) echo 'disabled';
    else {
      echo ' name="student_bio[contactperson]" required';
    }
    ?>
  ><?php if ($student) echo $student['contactperson']; ?></textarea>
</div>

<div class="form-group extra-curricular-group">
  <label class="control-label" for="extra_curricular">
    Extra Curricular Activities (separated by comma)
  </label>

  <textarea class="form-control" rows="2" id="extra_curricular"
    <?php
    if ($student) echo 'disabled';
    else {
      echo 'name="student_bio[activities]"';
    }
    ?>
  ><?php if ($student) echo $student['activities']; ?></textarea>
</div>
