<?php


Class A201505271432746814
{
  public function up(PDO $db)
  {

    $query0 = "ALTER TABLE student_courses CHANGE COLUMN `level` `level` VARCHAR(6) DEFAULT NULL";

    $query1 = "UPDATE student_courses SET level = 'ND I' WHERE level = 'OND1'";
    $query2 = "UPDATE student_courses SET level = 'ND II' WHERE level = 'OND2'";

    $query3 = "UPDATE student_courses SET level = 'HND I' WHERE level = 'HND1'";
    $query4 = "UPDATE student_courses SET level = 'HND II' WHERE level = 'HND2'";

    $db->query($query0);
    $db->query($query1);
    $db->query($query2);
    $db->query($query3);
    $db->query($query4);
  }

  public function down(PDO $db)
  {
  }
}
