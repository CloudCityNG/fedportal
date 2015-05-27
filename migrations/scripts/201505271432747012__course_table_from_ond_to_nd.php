<?php


Class A201505271432747012
{
  public function up(PDO $db)
  {

    $query0 = "alter table course_table change column `class` `class` VARCHAR(6) NOT NULL";

    $query1 = "UPDATE course_table SET class = 'ND I' WHERE class = 'OND1'";
    $query2 = "UPDATE course_table SET class = 'ND II' WHERE class = 'OND2'";

    $query3 = "UPDATE course_table SET class = 'HND I' WHERE class = 'HND1'";
    $query4 = "UPDATE course_table SET class = 'HND II' WHERE class = 'HND2'";

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
