<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 01-Feb-15
 * Time: 7:33 AM
 */

require_once(__DIR__ . '/databases.php');

function get_academic_departments()
{

  $db = get_db();

  $stmt = $db->query('SELECT * FROM academic_departments;');

  $returned_value = array();

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $returned_value[$row['code']] = $row['description'];
  }

  return $returned_value;
}
