<?php


Class A201505271432746685
{
  public function up(PDO $db)
  {
    $query0 = "alter table academic_levels change column  `code` `code` varchar(6) not null";

    $query1 = "UPDATE academic_levels SET code = 'ND I' WHERE code = 'OND1'";
    $query2 = "UPDATE academic_levels SET code = 'ND II' WHERE code = 'OND2'";

    $query3 = "UPDATE academic_levels SET code = 'HND I' WHERE code = 'HND1'";
    $query4 = "UPDATE academic_levels SET code = 'HND II' WHERE code = 'HND2'";

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
