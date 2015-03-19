<?php

Class A201503181426688064
{
  public function up(PDO $db)
  {
    $db->query(
      "ALTER TABLE student_courses
       ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
       ADD COLUMN `updated_at` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
       ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL"
    );

    $db->query("UPDATE student_courses SET `created_at` = NOW()");
  }

  public function down(PDO $db)
  {
  }
}
