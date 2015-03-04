<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 11-Feb-15
 * Time: 5:52 PM
 */
include_once(__DIR__ . '/StudentProfile.php');

class StudentProfileTest
{
  static function test_get_current_level_dept()
  {
    $profile = new StudentProfile('abcde');

    print_r($profile->get_current_level_dept());
  }

  static function test_student_exists()
  {
    echo StudentProfile::student_exists('abcde1');
  }

  static function test_get_billing_history()
  {
    $profile = new StudentProfile('abcde');

    print_r($profile->get_billing_history());
  }
}