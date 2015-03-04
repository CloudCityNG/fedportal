<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 01-Feb-15
 * Time: 7:16 AM
 */

require_once(__DIR__ . '/databases.php');

function get_current_academic_session() {

  $db = get_db();

  $stmt = $db->query("select code from academic_sessions order by id DESC limit 1;");

  return $stmt->fetch(PDO::FETCH_NUM)[0];
}