<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 01-Feb-15
 * Time: 5:04 PM
 */

require_once(__DIR__ . '/databases.php');
require_once(__DIR__ . '/app_settings.php');

function get_courses($semester_limit = null, $department_limit = null)
{
  $db = get_db();

  $log = get_logger("get-courses");

  $log->addInfo("semester limit = {$semester_limit}");
  $log->addInfo("department limit = {$department_limit}");

  if ($semester_limit && $department_limit) {
    $query = "select * from course_table
              where department = '$department_limit'
              and semester = '$semester_limit' ";

  } else if ($semester_limit && !$department_limit) {
    $query = "select * from course_table where semester = '$semester_limit' ";

  } else if (!$semester_limit && $department_limit) {
    $query = "select * from course_table where department = '$department_limit' ";

  } else {
    $query = "SELECT * FROM course_table";
  }

  $resource = $db->query($query);

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