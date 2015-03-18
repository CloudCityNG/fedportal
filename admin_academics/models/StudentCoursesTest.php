<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 18-Mar-15
 * Time: 3:51 PM
 */
require(__DIR__ . '/StudentCourses.php');

function test_bulk_create()
{
  $data = [
    [
      'academic_year_code' => '2014/2015',
      'reg_no' => 'academic_session',
      'semester' => '9',
      'course_id' => 242,
      'level' => 'OND4',
    ],

    [
      'academic_year_code' => '2014/2017',
      'reg_no' => 'academic_session',
      'semester' => '8',
      'course_id' => 245,
      'level' => 'OND8',
    ]
  ];

  print_r(StudentCourses::bulk_create($data));
}

//test_bulk_create();
