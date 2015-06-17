<?php


Class A201506171434517315
{
  public function up(PDO $db)
  {
    $query1 = "SELECT code FROM academic_levels";
    $stmt1 = $db->query($query1);

    $query2 = "UPDATE academic_levels SET description = :code1 WHERE code = :code";
    $code = $code1 = '';

    $stmt2 = $db->prepare($query2);
    $stmt2->bindParam('code', $code);
    $stmt2->bindParam('code1', $code1);

    if ($stmt1) {

      while ($row = $stmt1->fetch()) {
        $code = $code1 = $row['code'];
        $stmt2->execute();
      }
    }
  }

  public function down(PDO $db)
  {
  }
}
