<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 11-Feb-15
 * Time: 5:40 PM
 */
include_once(__DIR__ . '/AcademicYear.php');

function test_get_current_academic_year()
{
  $academic_years = new AcademicYear();

  echo $academic_years->get_current_year();
}

function test_get__years()
{
  $academic_years = new AcademicYear();

  print_r($academic_years->get_years(4));
}

test_get__years();