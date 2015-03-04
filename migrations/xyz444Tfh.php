<?php
/**
 * Created by maneptha on 28-Feb-15.
 */

require_once(__DIR__ . '/../helpers/databases.php');
require_once(__DIR__ . '/../helpers/app_settings.php');

class Migrations
{

  private $sql_files = [];

  private static $LOG_NAME = 'Migrations';

  private static $logger;

  function __construct()
  {
    $sql_file_pattern = "/\d+\.sql$/";

    $root = __DIR__;

    foreach (scandir($root) as $dir) {
      if (preg_match($sql_file_pattern, $dir)) {

        $this->sql_files[str_replace('.sql', '', $dir)] = file_get_contents("{$root}/{$dir}");
      }
    }

    self::$logger = get_logger(self::$LOG_NAME);

    $this->create_migration_table();

  }

  public function create_migration_table()
  {
    $db = get_db();
    $log = get_logger(self::$LOG_NAME);

    $query = "CREATE TABLE IF NOT EXISTS `migrations` (
                `number`     VARCHAR(3) NOT NULL,
                `sql_text`   TEXT       NOT NULL,
                `created_at` TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE `number` (`number`)
              );";

    self::do_logging("About to execute query: {$query}");

    try {
      $stmt = $db->query($query);

      if ($stmt) {

        self::do_logging("migration table query ran successfully!");
      }

    } catch (PDOException $e) {

      logPdoException($e, "Error occurred while creating migration table", $log);
      self::do_logging("migration table query did not run successfully!");
    }
  }

  private function insert_sql_texts()
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $key = '';

    $sql_text = '';

    $query = "INSERT INTO migrations (number, sql_text, created_at)
              VALUES (:key, :sql_text, NOW())";

    try {

      $stmt = $db->prepare($query);

      $stmt->bindParam(":key", $key);
      $stmt->bindParam(":sql_text", $sql_text);

      foreach ($this->sql_files as $key => $sql_text) {
        $stmt->execute();
      }

    } catch (PDOException $e) {
      logPdoException($e, "Error occurred while inserting sql texts", $log);
    }

  }

  public function migrate()
  {

    $db = get_db();

    $key = '';

    $sql_text = '';

    $query = "INSERT INTO migrations (number, sql_text, created_at)
              VALUES (:key, :sql_text, NOW())";


    try {

      $stmt = $db->prepare($query);

      $stmt->bindParam(":key", $key);
      $stmt->bindParam(":sql_text", $sql_text);

      foreach ($this->sql_files as $key => $sql_text) {
        if ($this->migration_exist($key)) {

          self::do_logging("migration already done for {$key}, it will not be migrated!");


        } else {

          self::do_logging("No migration for {$key}, we will now do the migration!");

          self::do_logging("About to run query: {$sql_text}");

          $sql_text_array = explode(';', $sql_text);

          foreach ($sql_text_array as $sql) {

            if (trim($sql)) {
              if ($db->query($sql)) {

                self::do_logging("Query ran successfully: {$sql}");

              } else {
                self::do_logging("Query did not run successfully: {$sql}");
              }
            }

          }

          self::do_logging("updating migration table for {$key}.");
          $stmt->execute();

        }
      }

    } catch (PDOException $e) {

      self::do_logging("Exception occurred while running query:<br>{$e->getMessage()}", 'error');
    }

  }

  private static function do_logging($log_text, $log_type = null, $log_params = [])
  {
    $param_text = print_r($log_params, true);
    echo "{$log_text} {$param_text}<br><br>";

    $log_type = $log_type ? ucfirst($log_type) : 'Info';

    call_user_func([self::$logger, "add{$log_type}"], $log_text, $log_params);
  }

  private static function migration_exist($number)
  {
    $db = get_db();

    $query = "SELECT COUNT(*) FROM migrations WHERE number = ?";

    $query_param = [$number];

    self::do_logging(
      "About to check if we have ran migration using query: {$query} and param: ",
      null,
      $query_param
    );

    $stmt = $db->prepare($query);

    $stmt->execute($query_param);

    return $stmt->fetchColumn();

  }

}


$mig = new Migrations;

$mig->migrate();
