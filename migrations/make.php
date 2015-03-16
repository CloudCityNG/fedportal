<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 16-Mar-15
 * Time: 6:19 AM
 */

if (count($argv) < 2) {
  throw new Exception('You must supply a name for your migration');
}

$file_name = $argv[1];

$pattern = '=[/?*;:{}\\\\]=';

if (preg_match($pattern, $file_name)) {
  throw new InvalidArgumentException("Migration name is not valid.");
}

require_once(__DIR__ . '/../vendor/autoload.php');

use Carbon\Carbon;

$now = Carbon::now();
$date_part = $now->format('Ymd') . $now->getTimestamp();

$file_name = substr($date_part . "__{$file_name}", 0, 53);

$class_name = 'A' . $date_part;

$stub = "<?php\n\n\nClass {$class_name}\n{\n  " .
  "public function up(PDO \$db) \n  {\n  }\n\n  " .
  "public function down(PDO \$db) \n  {\n  } \n}";

$file_obj = fopen(__DIR__ . "/scripts/{$file_name}.php", 'w');

fwrite($file_obj, $stub);

fclose($file_obj);
