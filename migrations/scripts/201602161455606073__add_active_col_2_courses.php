<?php


Class A201602161455606073
{
  public function up(PDO $db)
  {
    $query = "ALTER TABLE course_table ADD COLUMN `active` BOOLEAN NOT NULL DEFAULT 1";
    $db->query($query);
  }

  public function down(PDO $db)
  {
  }
}
