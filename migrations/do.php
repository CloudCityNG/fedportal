<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 16-Mar-15
 * Time: 7:54 AM
 */

require_once(__DIR__ . '/../helpers/databases.php');

class MigrationManager
{
  function __construct()
  {
    $this->create_migration_table();

    $this->run_migration_scripts();
  }

  private function create_migration_table()
  {
    $db = get_db();

    $db->query(
      "CREATE TABLE IF NOT EXISTS `migrations`
        (`number`     VARCHAR(100) NOT NULL,
         `created_at` TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP,

         UNIQUE `number` (`number`)
        )"
    );
  }

  public function run_migration_scripts()
  {
    $db = get_db();

    $scripts_dir = __DIR__ . '/scripts/';

    $script_pattern = "=^(\d{10,})__.+\.php$=";

    foreach (scandir($scripts_dir) as $script) {
      if (preg_match($script_pattern, $script, $matches)) {

        $query = "SELECT COUNT(*) FROM migrations WHERE number = '{$script}'";

        if (!$db->query($query)->fetchColumn()) {
          require_once($scripts_dir . $script);

          $class = "A{$matches[1]}";
          $obj = new $class();

          echo "<p>About to do migration for <strong>{$script}</strong></p>";

          $obj->up($db);

          $db->query("INSERT INTO migrations(number) VALUES('{$script}')");
        }

      }
    }

    echo "<h1>Forward migrations completed!</h1>";
  }
}

$migrate = new MigrationManager;
