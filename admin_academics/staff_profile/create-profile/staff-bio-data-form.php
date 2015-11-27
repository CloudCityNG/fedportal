<form role="form" method="post" class="well" id="bio-data-form" novalidate
      enctype="multipart/form-data"
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh">

  <fieldset>
    <legend>New Staff Profile</legend>

    <div class="form-group username-group">
      <label class="control-label" for="username">Username</label>

      <input class="form-control" type="text" name="staff_profile[username]" id="username" required
             data-fv-stringlength="true" data-fv-stringlength-min="3"/>
    </div>

    <div class="form-group first-name-group">
      <label class="control-label" for="first-name">First Name</label>

      <input class="form-control" type="text" name="staff_profile[first_name]" id="first-name" required
             data-fv-stringlength="true" data-fv-stringlength-min="3"/>
    </div>

    <div class="form-group last-name-group">
      <label class="control-label" for="last-name">Surname</label>

      <input class="form-control" type="text" name="staff_profile[last_name]" id="last-name"
             required data-fv-stringlength="true" data-fv-stringlength-min="3"/>
    </div>

    <div class="form-group other-names-group">
      <label class="control-label" for="other-names">Other Names (If applicable)</label>

      <input class="form-control" type="text" name="staff_profile[other_names]" id="other-names"/>
    </div>

    <div class="form-group previous-names-group">
      <label class="control-label" for="previous-names">Previous Names (if applicable)</label>

      <input class="form-control" type="text" name="staff_profile[previous_name]" id="previous-names"/>
    </div>

    <div class="form-group email-group">
      <label class="control-label" for="email">Email Address</label>

      <input class="form-control" type="email" name="staff_profile[email]" id="email"/>
    </div>

    <div class="form-group state-of-origin-group">
      <label class="control-label" for="state-of-origin">State of Origin</label>

      <select name="staff_profile[state]" id="state-of-origin" class="form-control">
        <option value="">-------------</option>

        <?php include(__DIR__ . '/../../../includes/nigeria-states.html') ?>
      </select>
    </div>

    <div class="form-group">
      <label class="control-label" for="gender">Gender</label>

      <select name="staff_profile[sex]" id="gender" class="form-control" required>
        <option value="">------</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
      </select>
    </div>

    <div class="form-group date-of-birth-group">
      <label class="control-label" for="date-of-birth-view">Date of Birth (dd-mmm-yyyy)</label>

      <div class="input-group date input-append show-date-picker">
        <input type="text" class="form-control" maxlength="11" name="date-of-birth-view" id="date-of-birth-view"
               required placeholder="dd-mmm-yyyy" pattern="^\d{1,2}-[A-Za-z]{3}-\d{4}$">

        <span class="input-group-addon add-on">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>

      <input type="hidden" id="date-of-birth" name="staff_profile[dateofbirth]"
             data-fv-date data-fv-date-format="YYYY-MM-DD" data-fv-excluded="false"/>
    </div>

    <div class="form-group local-govt-group">
      <label for="local-govt">Local Government Area</label>

      <input type="text" name="staff_profile[lga]" id="local-govt" class="form-control" required/>
    </div>

    <div class="form-group permanent-address-group">
      <label for="permanent-address" class="control-label">Permanent Address</label>

      <textarea name="staff_profile[permanent_address]" id="permanent-address" rows="4" class="form-control"
                required></textarea>
    </div>

    <div class="form-group mobile-phone-group">
      <label for="mobile-phone" class="control-label">Mobile Phone</label>

      <input type="text" name="staff_profile[phone]" id="mobile-phone" class="form-control" required
             placeholder="E.g +802345695" pattern="\+?\d{4,}"/>
    </div>

    <div class="form-group emergency-contact-address-group">
      <label class="control-label" for="emergency-contact-address">
        Name/Phone/Address of contact person in case of emergency ( state relationship)
      </label>

      <textarea name="staff_profile[contactperson]" id="emergency-contact-address" rows="4"
                class="form-control" required></textarea>
    </div>

  </fieldset>

  <fieldset>
    <legend>Upload Your Photo</legend>

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
