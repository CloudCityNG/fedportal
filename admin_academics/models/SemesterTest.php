<?php

require_once(__DIR__ . '/Semester.php');

function test_get_latest_semester_end_date()
{
  return Semester::getLatestSemesterEndDate();
}


function test_validate_dates_dates_not_set()
{
  return Semester::validateDates([]);
}

function test_validate_dates_Start_date_not_set_but_end_date_is_set()
{
  return Semester::validateDates(['end_date' => '03-05-2015']);
}

function test_validate_dates_Start_date_is_set_but_end_date_not_set()
{
  return Semester::validateDates(['start_date' => '03-05-2015']);
}

function test_validate_dates_dates_empty()
{
  return Semester::validateDates([
    'start_date' => '', 'end_date' => ''
  ]);
}


function test_validate_dates_start_date_empty_end_date_not_empty()
{
  return Semester::validateDates([
    'start_date' => '', 'end_date' => '03-05-2015'
  ]);
}

function test_validate_dates_start_date_not_empty_end_date_empty()
{
  return Semester::validateDates([
    'start_date' => '03-05-2015', 'end_date' => ''
  ]);
}

function test_validate_dates_invalid_date_string_for_start_date()
{
  return Semester::validateDates([
    'start_date' => '03-05-2015x', 'end_date' => '03-05-2015'
  ]);
}

function test_validate_dates_invalid_date_string_for_end_date()
{
  return Semester::validateDates([
    'start_date' => '03-05-2015', 'end_date' => '03-05-2015x'
  ]);
}

function test_validate_dates_dates_are_equal()
{
  return Semester::validateDates([
    'start_date' => '03-05-2015', 'end_date' => '03-05-2015'
  ]);
}

function test_validate_dates_start_date_after_end_date()
{
  return Semester::validateDates([
    'start_date' => '04-05-2015', 'end_date' => '03-05-2015'
  ]);
}

function test_validate_dates_start_date_before_latest_semester_date()
{
  $latest_semester_date = Semester::getLatestSemesterEndDate();

  return Semester::validateDates([
    'start_date' => $latest_semester_date->subDays(4)->format('d-m-Y'),
    'end_date' => $latest_semester_date->addDays(30)->format('d-m-Y')
  ]);
}

function test_validate_dates_all_dates_valid()
{
  $latest_semester_date = Semester::getLatestSemesterEndDate();

  $latest_semester_date = $latest_semester_date ? $latest_semester_date : \Carbon\Carbon::now();

  return Semester::validateDates([
    'start_date' => $latest_semester_date->addDays(4)->format('d-m-Y'),
    'end_date' => $latest_semester_date->addDays(30)->format('d-m-Y')
  ]);
}

function testGetImmediatePastSemester()
{
  return Semester::getImmediatePastSemester();
}

function testGetCurrentSemester()
{
  print_r(Semester::getCurrentSemester());
}

function testGetSemesterByIds()
{
  return Semester::getSemesterByIds([2, 5], true);
}

function testGetSessionIDsFromSemesterIDs()
{
  print_r(Semester::getSessionIDsFromSemesterIDs([20]));
}

//testGetSessionIDsFromSemesterIDs();

//print_r(testGetSemesterByIds());

//testGetCurrentSemester();

//print_r(testGetImmediatePastSemester());

//print_r(test_validate_dates_start_date_after_latest_semester_date());

//print_r(test_validate_dates_start_date_after_end_date());

//print_r(test_validate_dates_dates_are_equal());

//print_r(test_validate_dates_invalid_date_string_for_end_date());

//print_r(test_validate_dates_invalid_date_string_for_start_date());

//print_r(test_validate_dates_start_date_not_empty_end_date_empty());

//print_r(test_validate_dates_start_date_empty_end_date_not_empty());

//print_r(test_validate_dates_dates_empty());

//print_r(test_get_latest_semester_end_date());

//print_r(test_validate_dates_dates_not_set());

//print_r(test_validate_dates_Start_date_not_set_but_end_date_is_set());

//print_r(test_validate_dates_Start_date_is_set_but_end_date_not_set());
