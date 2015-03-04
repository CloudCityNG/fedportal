<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 31-Jan-15
 * Time: 11:10 PM
 */

require_once(__DIR__ . '/databases.php');

function get_academic_levels($limit_to = null)
{
  $db = get_db();

  $limit_query = !$limit_to ? '' : "where code like '%$limit_to%'";

  $stmt = $db->query("select * from academic_levels $limit_query;");

  $results = [];

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $results[$row['id']] = $row['code'];
  }

  return $results;
}