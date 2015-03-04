<input name="student_bio[personalno]" type="hidden" value="<?php echo $reg_no; ?>">

<div class="form-group">
  <label class="control-label" for="first-name">First Name</label>

  <input class="form-control" type="text" name="student_bio[first_name]" id="first-name"
         required data-minLength="2" data-minLength-error="Invalid first name"/>
</div>

<div class="form-group">
  <label class="control-label" for="surname">Surname</label>

  <input class="form-control" type="text" name="student_bio[surname]" id="surname"
         required data-minLength="2" data-minLength-error="Invalid surname"/>
</div>

<div class="form-group">
  <label class="control-label" for="other_names">Other Names (If applicable)</label>

  <input class="form-control" type="text" name="student_bio[other_names]" id="other_names"/>
</div>

<div class="form-group">
  <label class="control-label" for="previous-names">Previous Names (if applicable)</label>

  <input class="form-control" type="text" name="student_bio[previousname]" id="previous-names"/>
</div>

<div class="form-group">
  <label class="control-label" for="email">Email Address</label>

  <input class="form-control" type="email" name="student_bio[email]" id="email" required
         value="<?php echo $email; ?>"/>
</div>


<div class="form-group">
  <label class="control-label" for="academic_session_id">Admission Year</label>

  <select name="student_bio[currentsession]" id="academic_session_id" class="form-control" required>
    <option value="">---------------</option>

    <?php
    include_once(__DIR__ . '/../../helpers/get_academic_sessions.php');

    foreach (get_academic_sessions() as $academic_session) {

      echo "<option value='$academic_session[2]'>$academic_session[2]</option>";
    }
    ?>
  </select>
</div>

<div class="form-group">
  <label class="control-label" for="nationality">Nationality</label>

  <input type="text" value="Nigerian" name="student_bio[nationality]" id="nationality" required
         class="form-control"/>
</div>

<div class="form-group">
  <label class="control-label" for="state_of_origin">State of Origin</label>

  <select name="student_bio[state]" id="state_of_origin" class="form-control" required>
    <option value="">-------------</option>

    <?php echo file_get_contents(__DIR__ . '/../nigeria-states.html') ?>
  </select>
</div>

<div class="form-group">
  <label class="control-label" for="academic_department_id">Course of Study</label>

  <select name="student_bio[course]" id="academic_department_id" class="form-control" required>
    <option value="">--------------</option>

    <option value="dental_technology">Dental Technology</option>

    <option value="dental_therapy">Dental Therapy</option>
  </select>
</div>

<div class="form-group">
  <label class="control-label" for="gender">Gender</label>

  <select name="student_bio[sex]" id="gender" class="form-control" required>
    <option value="">------</option>
    <option value="male">Male</option>
    <option value="female">Female</option>
  </select>
</div>

<div class="form-group">
  <label class="control-label" for="date_of_birth">Date of Birth (dd-mm-yyyy)</label>

  <input type="text" class="form-control" name="student_bio[dateofbirth]" id="date_of_birth"
         required placeholder="dd-mm-yyyy" pattern="\d{1,2}-\d{1,2}-\d{4}">
</div>

<div class="form-group">
  <label for="local_govt">Local Government Area</label>

  <input type="text" name="student_bio[lga]" id="local_govt" class="form-control" required/>
</div>

<div class="form-group">
  <label for="permanent_address" class="control-label">Permanent Address</label>
  <textarea name="student_bio[permanentaddress]" id="permanent_address" rows="4" class="form-control"
            required></textarea>
</div>

<div class="form-group">
  <label for="mobile_phone" class="control-label">Mobile Phone</label>

  <input type="text" name="student_bio[phone]" id="mobile_phone" class="form-control"
         required pattern="\+?\d{4,}" data-native-error="Invalid phone number"/>
</div>

<div class="form-group">
  <label class="control-label" for="guardian">Parent/Guardian</label>
  <input class="form-control" type="text" name="student_bio[parentname]" id="guardian" required/>
</div>

<div class="form-group">
  <label class="control-label" for="emergency_contact_address">
    Name/Phone/Address of contact person in case of emergency ( state relationship)
  </label>

  <textarea name="student_bio[contactperson]" id="emergency_contact_address" rows="4"
            class="form-control" required></textarea>
</div>

<div class="form-group">
  <label class="control-label" for="extra_curricular">
    Extra Curricular Activities (separated by comma)
  </label>

  <textarea name="student_bio[activities]" id="extra_curricular" rows="2"
            class="form-control"></textarea>
</div>