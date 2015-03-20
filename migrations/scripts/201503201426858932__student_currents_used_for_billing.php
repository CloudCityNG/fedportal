<?php


Class A201503201426858932
{
  public function up(PDO $db)
  {
    $query1 = "ALTER TABLE student_currents
              ADD COLUMN amount DECIMAL(13,2) NULL DEFAULT NULL";

    $db->query($query1);
  }

  public function down(PDO $db)
  {
  }
}
