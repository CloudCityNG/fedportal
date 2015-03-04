<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 31-Jan-15
 * Time: 11:10 PM
 */

require_once(__DIR__ . '/databases.php');

function get_academic_sessions()
{
  $db = get_db();

  $stmt = $db->query('SELECT * FROM academic_sessions ORDER BY start_year DESC');

  $results = array();

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $results[$row['id']] = array($row['start_year'], $row['end_year'], $row['code']);
  }

  return $results;
}
