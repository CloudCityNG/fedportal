<?php
require(__DIR__ . '/form-post-message.php');
echo $createStaffProfilePostMessage;
?>

<form role="form" method="post" class="well" id="staff-profile-create-form" novalidate
      data-fv-framework="bootstrap"
      data-fv-message="This value is not valid"
      data-fv-icon-valid="glyphicon glyphicon-ok"
      data-fv-icon-invalid="glyphicon glyphicon-remove"
      data-fv-icon-validating="glyphicon glyphicon-refresh">

  <fieldset>
    <?php
    $staffProfile = isset($createStaffProfileContext['staff_profile']) ? $createStaffProfileContext['staff_profile'] : null;
    $editProfile = isset($createStaffProfileContext['edit']) ? $createStaffProfileContext['edit'] : null;
    $disabled = '';
    $domEditView = '';
    $legend = 'Create New Staff Profile';
    $editIcon = '<span class="input-group-addon"></span>';
    $editIconCapabilities = '';
    $submitBtnLabel = 'Create Profile';

    if ($editProfile) {
      $submitBtnLabel = 'Edit Profile';
      $disabled = 'disabled';
      $legend = 'Edit Staff Profile';

      $editIcon = '
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-pencil toggle-form-control-edit" style="cursor:pointer;"></span>
          <span class="glyphicon glyphicon-eye-open toggle-form-control-edit" style="cursor:pointer;display: none;"></span>
        </span>
      ';

      $editIconCapabilities = '
        <span class="glyphicon glyphicon-pencil toggle-capabilities-edit" style="cursor:pointer;"></span>
        <span class="glyphicon glyphicon-eye-open toggle-capabilities-edit" style="cursor:pointer; display: none;"></span>
      ';

      $staffProfileJson = json_encode($staffProfile);
      echo "<input type='hidden' value='{$staffProfileJson}' id='staff-profile-data' name='staff_profile_data' />";
    }

    echo "<legend>{$legend}</legend>";
    ?>

    <div class="form-group username-group">
      <label class="control-label" for="username">Username</label>

      <div class="input-group">
        <input class="form-control check-original-profile-data" type="text" name="staff_profile[username]" id="username"
               required value="<?php if ($staffProfile) echo $staffProfile['username']; ?>" <?php echo $disabled; ?>
               data-fv-stringlength="true" data-fv-stringlength-min="3"/>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group first-name-group">
      <label class="control-label" for="first-name">First Name</label>

      <div class="input-group">
        <input class="form-control check-original-profile-data" type="text" name="staff_profile[first_name]"
               id="first-name" required
               value="<?php if ($staffProfile) echo $staffProfile['first_name']; ?>" <?php echo $disabled; ?>
               data-fv-stringlength="true" data-fv-stringlength-min="3"/>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group last-name-group">
      <label class="control-label" for="last-name">Last Name</label>

      <div class="input-group">
        <input class="form-control check-original-profile-data" type="text" name="staff_profile[last_name]"
               id="last-name" required
               value="<?php if ($staffProfile) echo $staffProfile['last_name']; ?>" <?php echo $disabled; ?>
               data-fv-stringlength="true" data-fv-stringlength-min="3"/>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group password-group">
      <label class="control-label" for="password">Password</label>

      <div class="input-group">
        <input class="form-control check-original-profile-data" type="password" name="staff_profile[password]"
               id="password" required
          <?php echo $disabled; ?> data-fv-stringlength="true" data-fv-stringlength-min="6"/>

        <?php echo $editIcon; ?>
      </div>
    </div>

    <div class="form-group confirm-password-group">
      <label class="control-label" for="confirm-password">Confirm Password</label>

      <div class="input-group">
        <input class="form-control check-original-profile-data" type="password" name="staff_profile[confirm_password]"
               id="confirm-password" required
               data-fv-stringlength="true" data-fv-stringlength-min="6"
               data-fv-identical="true" <?php echo $disabled; ?>
               data-fv-identical-field="staff_profile[password]"
               data-fv-identical-message="Password and confirm password must the same"/>

        <?php echo $editIcon; ?>
      </div>
    </div>

  </fieldset>

  <?php if (UserSession::isCapable('can_assign_capability_to_staff')) require(__DIR__ . '/assign-capabilities-form.php') ?>

  <div class="text-center" style="margin-top: 30px;">
    <button class="btn" type="submit" name="submit-btn" id="submit-btn" <?php echo $disabled; ?>>
      <?php echo $submitBtnLabel; ?>
    </button>
  </div>
</form>
