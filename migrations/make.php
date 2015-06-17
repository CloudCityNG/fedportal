<?php

if (count($argv) < 2) {
  throw new Exception('You must supply a name for your migration');
}

$fileName = $argv[1];

$pattern = '=[/?*;:{}\\\\]=';

if (preg_match($pattern, $fileName)) {
  throw new InvalidArgumentException("Migration name is not valid.");
}

require(__DIR__ . '/../vendor/autoload.php');

use Carbon\Carbon;

$now = Carbon::now();
$datePart = $now->format('Ymd') . $now->getTimestamp();

$fileName = substr($datePart . "__{$fileName}", 0, 53);

$className = 'A' . $datePart;

$stub = "<?php\n\n\nClass {$className}\n{\n  " .
  "public function up(PDO \$db) \n  {\n  }\n\n  " .
  "public function down(PDO \$db) \n  {\n  } \n}";

$fileObj = fopen(__DIR__ . "/scripts/{$fileName}.php", 'w');

fwrite($fileObj, $stub);

fclose($fileObj);
