<?php


Class A201508161439754600
{
  public function up(PDO $db)
  {
    $db->query(
      "ALTER TABLE pics
       ADD COLUMN id INT NULL DEFAULT NULL FIRST,
       ADD COLUMN `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
       ADD COLUMN `updated_at` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
       ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL");

    $db->query("UPDATE pics SET `created_at` = NOW()");

    $count = $db->query("SELECT personalno FROM pics WHERE id IS NULL");

    $query = "UPDATE pics
                SET id = :id
              WHERE personalno = :reg_no
              AND id IS NULL ";

    $id = 0;
    $reg_no = '';

    $stmt = $db->prepare($query);
    $stmt->bindParam('id', $id);
    $stmt->bindParam('reg_no', $reg_no);

    foreach ($count->fetchAll() as $value) {
      ++$id;
      $reg_no = $value['personalno'];
      $stmt->execute();
    }

    $db->query(
      "ALTER TABLE pics
       DROP PRIMARY KEY"
    );

    $db->query(
      "ALTER TABLE `pics`
       CHANGE COLUMN `id` `id` INT NOT NULL AUTO_INCREMENT,
       ADD PRIMARY KEY (`id`),
       ADD UNIQUE INDEX `pics_personalno`(`personalno`)"
    );
  }

  public function down(PDO $db)
  {
  }
}
