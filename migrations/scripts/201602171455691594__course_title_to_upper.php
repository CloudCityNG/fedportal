<?php


Class A201602171455691594
{
  public function up(PDO $db)
  {
    $stmt1 = $db->query("SELECT id, title FROM course_table");
    $result = $stmt1->fetchAll();
    $stmt1->closeCursor();

    $query2 = "UPDATE course_table SET title=:title WHERE id=:id";
    $stmt2 = $db->prepare($query2);
    $id = '';
    $title = '';
    $stmt2->bindParam('id', $id);
    $stmt2->bindParam('title', $title);

    foreach ($result as $row) {
      $id = $row['id'];
      $title = strtoupper($row['title']);
      $stmt2->execute();
    }
  }

  public function down(PDO $db)
  {
  }
}
