<?php


Class A201503201426865100
{
  public function up(PDO $db)
  {
    $db->query("ALTER TABLE `freshman_profile`  ADD `id` INT NULL DEFAULT NULL  FIRST");

    $count = $db->query("SELECT personalno FROM freshman_profile WHERE id IS NULL");

    $query = "UPDATE freshman_profile
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
  }

  public function down(PDO $db)
  {
  }
}
