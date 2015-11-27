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
    <legend>New Staff Profile</legend>

    <?php
    $staffProfile = isset($createStaffProfileContext['staff_profile']) ? $createStaffProfileContext['staff_profile'] : null
    ?>

    <div class="form-group username-group">
      <label class="control-label" for="username">Username</label>

      <input class="form-control" type="text" name="staff_profile[username]" id="username" required
             value="<?php if($staffProfile) echo $staffProfile['username']; ?>"
             data-fv-stringlength="true" data-fv-stringlength-min="3"/>
    </div>

    <div class="form-group first-name-group">
      <label class="control-label" for="first-name">First Name</label>

      <input class="form-control" type="text" name="staff_profile[first_name]" id="first-name" required
             value="<?php if($staffProfile) echo $staffProfile['first_name']; ?>"
             data-fv-stringlength="true" data-fv-stringlength-min="3"/>
    </div>

    <div class="form-group last-name-group">
      <label class="control-label" for="last-name">Last Name</label>

      <input class="form-control" type="text" name="staff_profile[last_name]" id="last-name" required
             value="<?php if($staffProfile) echo $staffProfile['last_name']; ?>"
              data-fv-stringlength="true" data-fv-stringlength-min="3"/>
    </div>

    <div class="form-group password-group">
      <label class="control-label" for="password">Password</label>

      <input class="form-control" type="password" name="staff_profile[password]" id="password" required
             data-fv-stringlength="true" data-fv-stringlength-min="6"/>
    </div>

    <div class="form-group confirm-password-group">
      <label class="control-label" for="confirm-password">Confirm Password</label>

      <input class="form-control" type="password" name="staff_profile[confirm_password]" id="confirm-password" required
             data-fv-stringlength="true" data-fv-stringlength-min="6" data-fv-identical="true"
             data-fv-identical-field="staff_profile[password]"
             data-fv-identical-message="Password and confirm password must the same"/>
    </div>

  </fieldset>

  <?php if ($isSuperUser) require(__DIR__ . '/assign-capabilities-form.php') ?>

  <div class="text-center" style="margin-top: 30px;">
    <button class="btn" type="submit" name="submit-btn">Create Profile</button>
  </div>
</form>
