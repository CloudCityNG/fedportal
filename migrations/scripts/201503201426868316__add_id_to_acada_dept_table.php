<?php


Class A201503201426868316
{
  public function up(PDO $db)
  {
    $db->query(
      "ALTER TABLE academic_departments
       ADD COLUMN id INT NULL DEFAULT NULL FIRST"
    );

    $stmt1 = $db->query(
      "SELECT code FROM academic_departments WHERE id IS NULL"
    );

    $query = "UPDATE academic_departments SET id = :id
              WHERE id IS NULL
              AND code = :code";

    $stmt2 = $db->prepare($query);

    $code = '';
    $id = 0;
    $stmt2->bindParam('code', $code);
    $stmt2->bindParam('id', $id);

    foreach ($stmt1->fetchAll() as $dept) {
      $code = $dept['code'];
      ++$id;

      $stmt2->execute();
    }

    $db->query(
      "ALTER TABLE academic_departments
       CHANGE COLUMN id id INT NOT NULL AUTO_INCREMENT,
       ADD PRIMARY KEY(id)"
    );

  }

  public function down(PDO $db)
  {
  }
}
