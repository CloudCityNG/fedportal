<?php


Class A201506111434003673
{
  public function up(PDO $db)
  {
    $query = "ALTER TABLE student_courses ADD COLUMN `publish` BOOLEAN NOT NULL DEFAULT 0";
    $db->query($query);
  }

  public function down(PDO $db)
  {
  }
}
