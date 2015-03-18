<?php

Class A201503181426688064
{
  public function up(PDO $db)
  {
    $db->query(
      "ALTER TABLE student_courses
       ADD COLUMN `created_at` DATETIME NULL,
       ADD COLUMN `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
       ADD COLUMN `deleted_at` DATETIME NULL"
    );

    $db->query("UPDATE student_courses SET `created_at` = NOW()");

    $db->query(
      "ALTER TABLE student_courses
       CHANGE COLUMN `created_at` `created_at` DATETIME NOT NULL"
    );
  }

  public function down(PDO $db)
  {
  }
}
