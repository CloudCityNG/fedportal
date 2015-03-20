<?php


Class A201503201426871125
{
  public function up(PDO $db)
  {
    $db->query(
      "DELETE FROM freshman_profile WHERE email = ''"
    );
  }

  public function down(PDO $db)
  {
  }
}
