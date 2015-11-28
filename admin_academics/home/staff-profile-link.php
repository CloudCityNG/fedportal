<div class="side-nav staff-profile-side-bar-nav">
  <span class="title">Manage Staff Profile</span>

  <div class="links">
    <?php
    if (UserSession::isCapable('can_list_staff_profile')){
      $listStaffLink = path_to_link(__DIR__ . '/../staff_profile/') . '?list-staff';
      echo "<a class='link' href='{$listStaffLink}'>List of staff</a>";
    }

    if (UserSession::isCapable('can_create_staff_profile')){
      $createProfileLink = path_to_link(__DIR__ . '/../staff_profile/') . '?create-profile';
      echo "<a class='link' href='{$createProfileLink}'>Create Staff Profile</a>";
    }
    ?>
  </div>
</div>
