<?php

require_once(__DIR__ . '/AcademicDepartment.php');

function test_get_academic_departments()
{
  print_r(AcademicDepartment::getAcademicDepartments());
}

//test_get_academic_departments();
