<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/app_settings.php');

use Monolog\Logger;

function get_db()
{
  $db_name = DB_NAME;

  return new PDO(
    "mysql:host=localhost;dbname={$db_name};charset=utf8", DB_USERNAME, DB_PASSWORD,
    [
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

function logPdoException(PDOException $e, $message, Logger $log)
{
  $log->addError($message);
  $log->addError('Error code is ' . $e->getCode());
  $log->addError($e->getMessage());
  $log->addError('Stack trace:', $e->getTrace());
}

function log_sql($query, $params, $log_level)
{
}

//use Illuminate\Database\Capsule\Manager as Capsule;
//
//$capsule = new Capsule();
//
//$capsule->addConnection([
//  'driver' => 'mysql',
//  'host' => 'localhost',
//  'database' => $db_name,
//  'username' => $username,
//  'password' => $passwd,
//  'charset' => 'utf8',
//  'collation' => 'utf8_unicode_ci',
//]);
//
//$capsule->setAsGlobal();
//
//$capsule->bootEloquent();
