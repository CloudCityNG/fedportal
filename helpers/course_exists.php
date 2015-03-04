<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 01-Feb-15
 * Time: 8:22 PM
 */

require_once(__DIR__ . '/databases.php');

function course_exists($academic_year, $reg_no, $semester)
{
  $db = get_db();

  $data = [];

  $stmt = $db->query(
    "select title, code, unit from student_courses join course_table on (course_id = course_table.id)
     where reg_no = '$reg_no' and
     academic_year_code = '$academic_year' and
     student_courses.semester = '$semester' ;"
  );

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $row_data['title'] = $row['title'];
    $row_data['code'] = $row['code'];
    $row_data['unit'] = $row['unit'];

    $data[] = $row_data;
  }

  return $data;

}
