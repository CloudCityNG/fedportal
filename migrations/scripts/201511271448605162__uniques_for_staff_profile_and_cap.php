<?php


Class A201511271448605162
{
  public function up(PDO $db)
  {
    $db->query(
      "ALTER TABLE staff_profile
       ADD UNIQUE (username),
       ADD UNIQUE (pic)
      "
    );

    $db->query(
      "ALTER TABLE staff_capability
       ADD UNIQUE (code)
      "
    );

    $db->query(
      "ALTER TABLE staff_capability_assign
       ADD UNIQUE (staff_profile_id, staff_capability_id)
      "
    );
  }

  public function down(PDO $db)
  {
  }
}
