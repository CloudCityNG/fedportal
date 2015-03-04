<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 01-Feb-15
 * Time: 5:04 PM
 */

require_once(__DIR__ . '/databases.php');

function get_courses($semester_limit = null, $department_limit = null)
{
  $semester_limit_text = !$semester_limit ? '' : "where semester = '$semester_limit'";

  $dept_limit_text = !$department_limit ? '' : " and department = '$department_limit' ";

  $db = get_db();

  $resource = $db->query(
    "select * from course_table $semester_limit_text $dept_limit_text");

  $result = [];

  if ($resource) {
    while ($row = $resource->fetch(PDO::FETCH_ASSOC)) {

      $data['code'] = $row['code'];
      $data['title'] = $row['title'];
      $data['unit'] = $row['unit'];
      $data['id'] = $row['id'];

      $class = $row['class'];

      if (array_key_exists($class, $result)) {
        $result[$class][] = $data;

      } else {
        $result[$class] = [$data];
      }

    }

    return $result;
  }

  return null;

}