<?php


Class A201503201426866693
{
  public function up(PDO $db)
  {
    $db->query(
      "ALTER TABLE freshman_profile
       DROP PRIMARY KEY"
    );

    $db->query(
      "ALTER TABLE `freshman_profile`
       CHANGE COLUMN `id` `id` INT NOT NULL AUTO_INCREMENT,
       ADD PRIMARY KEY (`id`),
       ADD UNIQUE INDEX `freshman_profile_personalno`(`personalno`)"
    );
  }

  public function down(PDO $db)
  {
  }
}
