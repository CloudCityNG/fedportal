<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 18-Mar-15
 * Time: 10:24 PM
 */

require_once(__DIR__ . '/AcademicDepartment.php');

function test_get_academic_departments()
{
  print_r(AcademicDepartment::get_academic_departments());
}

test_get_academic_departments();
