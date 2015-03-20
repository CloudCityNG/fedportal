<?php


Class A201503201426864496
{
  public function up(PDO $db)
  {
    $db->query("DROP TABLE IF EXISTS student_billing");
  }

  public function down(PDO $db)
  {
  }
}
