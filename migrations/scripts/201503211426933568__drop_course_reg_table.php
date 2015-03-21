<?php


Class A201503211426933568
{
  public function up(PDO $db)
  {
    $stmt = $db->query("DROP TABLE IF EXISTS course_reg");
    $stmt->closeCursor();
  }

  public function down(PDO $db)
  {
  }
}
