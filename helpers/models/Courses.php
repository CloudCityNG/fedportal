<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 05-Feb-15
 * Time: 8:12 PM
 */

include_once('Models.php');

require_once(__DIR__ . '/../databases.php');

class Courses extends Models
{
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
    $db = get_db();

    $stmt = $db->prepare(
      "SELECT * FROM student_courses WHERE reg_no = ? LIMIT 1"
    );

    $stmt->execute([$reg_no]);

    return $stmt->rowCount();

  }

}
