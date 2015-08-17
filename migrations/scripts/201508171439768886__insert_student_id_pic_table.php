<?php


Class A201508171439768886
{
  public function up(PDO $db)
  {
    $db->query("ALTER TABLE pics ADD COLUMN `freshman_profile_id` INT NULL DEFAULT NULL");

    $stmtStudent = $db->query("SELECT id, personalno FROM freshman_profile");
    $students = $stmtStudent->fetchAll();

    $stmtUpdate = $db->prepare("UPDATE pics SET freshman_profile_id = :id WHERE personalno = :freshman_profile_id");
    $freshman_profile_id = '';
    $id = '';
    $stmtUpdate->bindParam('id', $id);
    $stmtUpdate->bindParam('freshman_profile_id', $freshman_profile_id);

    foreach ($students as $student) {
      $id = $student['id'];
      $freshman_profile_id = $student['personalno'];

      $stmtUpdate->execute();
    }

    $db->query("DELETE FROM pics WHERE freshman_profile_id IS NULL");

    $db->query("ALTER TABLE pics
                CHANGE COLUMN freshman_profile_id freshman_profile_id INT NOT NULL,
                ADD CONSTRAINT `fk_pics_freshman_profile_id`
                FOREIGN KEY (`freshman_profile_id`) REFERENCES freshman_profile(`id`)");

  }

  public function down(PDO $db)
  {
  }
}
