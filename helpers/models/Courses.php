<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 05-Feb-15
 * Time: 8:12 PM
 */

include_once('Models.php');

class Courses extends Models {

  protected $table = 'student_courses';

  protected $db_attributes = [
    'id',

    'academic_year_code',

    'reg_no',

    'semester',

    'course_id',
  ];

  protected $guarded = ['id'];

  public function exists($reg_no)
  {
    global $db;

    $stmt = $db->prepare(
      "select * from student_courses where reg_no = ? limit 1"
    );

    $stmt->execute([$reg_no]);

    return $stmt->rowCount();

  }

}