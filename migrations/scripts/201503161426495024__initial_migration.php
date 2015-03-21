<?php


Class A201503161426495024
{
  public function up(PDO $db)
  {
    $sql_text = file_get_contents(__DIR__ . '/../initial.sql');

    foreach (explode(';', $sql_text) as $sql) {
      if (trim($sql)) {
        echo "<h3>Executing initial sql:</h3><br/>{$sql}<br/><br/>";
        $stmt = $db->query($sql);
        $stmt->closeCursor();
      }

    }
  }

  public function down($db)
  {
  }
}
