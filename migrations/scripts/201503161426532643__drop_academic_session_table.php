<?php


Class A201503161426532643
{
  public function up(PDO $db)
  {
    $stmt = $db->query("DROP TABLE IF EXISTS academic_sessions");
    $stmt->closeCursor();
  }

  public function down(PDO $db)
  {
  }
}
