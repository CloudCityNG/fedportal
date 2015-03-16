<?php


Class A201503161426495024
{
  public function up(PDO $db)
  {
    $sql_text = file_get_contents(__DIR__ . '/../initial.sql');

    foreach (explode(';', $sql_text) as $sql) {
      if (trim($sql)) {
        $db->query($sql);
      }

    }
  }

  public function down($db)
  {
  }
}
