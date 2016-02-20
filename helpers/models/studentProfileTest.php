<?php

include_once(__DIR__ . '/StudentProfile.php');

class StudentProfileTest
{
  static function test_get_current_level_dept()
  {
    $profile = new StudentProfile('abcde');

    print_r($profile->getCurrentLevelDept());
  }

  static function test_student_exists()
  {
    echo StudentProfile::exists('abcde1');
  }

  static function test_get_billing_history()
  {
    $profile = new StudentProfile('abcde');

    print_r($profile->get_billing_history());
  }
}
