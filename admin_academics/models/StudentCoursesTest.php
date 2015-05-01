<?php

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

//  print_r(StudentCourses::bulk_create_for_student_for_semester($data));
}

//test_bulk_create();
