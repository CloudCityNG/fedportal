<?php
require_once(__DIR__ . '/StaffProfile.php');

function testGetAllStaff()
{
  print_r(StaffProfile::getAllStaff());
}

//testGetAllStaff();


function testStaffExistsReturnTrue()
{
  echo StaffProfile::staffExists(['username' => 'admin']) . "\n";
}

//testStaffExistsReturnTrue();

function testStaffExistsReturnFalse()
{
  echo StaffProfile::staffExists(['username' => 'adminx']) . "\n";
}

//testStaffExistsReturnFalse();


function testStaffExistsReturnFalse1()
{
  echo StaffProfile::staffExists([]) . "\n";
}

//testStaffExistsReturnFalse1();

function testStaffExistsReturnTrue1()
{
  echo StaffProfile::staffExists(['username' => 'admin', 'id' => 1]) . "\n";
}
//testStaffExistsReturnTrue1();

function testCreateProfile(){
  print_r(
    StaffProfile::createProfile(['username' => 'adminx', 'password' => 111111, 'first_name' => 'kanmii'])
  );
  echo "\n" . StaffProfile::deleteProfile('adminx');
}
//testCreateProfile();
