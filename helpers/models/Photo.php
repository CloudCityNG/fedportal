<?php

include_once('Models.php');

include_once(__DIR__ . '/../databases.php');

class Photo extends Models {

  protected $table = 'pics';

  protected $db_attributes = ['personalno', 'nameofpic',];

  public function exists($reg_no)
  {
    $db = get_db();

    $stmt = $db->prepare(
      "select * from $this->table WHERE personalno = ?"
    );

    $stmt->execute([$reg_no]);

    return $stmt->rowCount();
  }

}
