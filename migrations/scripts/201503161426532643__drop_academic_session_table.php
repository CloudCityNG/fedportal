<?php


Class A201503161426532643
{
  public function up(PDO $db)
  {
    $db->query("DROP TABLE academic_sessions");
  }

  public function down(PDO $db)
  {
  }
}
