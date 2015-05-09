<?php


Class A201505091431166360
{
  public function up(PDO $db)
  {
    $query = "ALTER TABLE student_courses ADD  COLUMN `score` DECIMAL(5, 2) DEFAULT NULL";
    $stmt = $db->query($query);
    $stmt->closeCursor();
  }

  public function down(PDO $db)
  {
  }
}
