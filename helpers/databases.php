<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 05-Feb-15
 * Time: 6:06 PM
 */

//include_once(__DIR__ . '/../vendor/autoload.php');

use Monolog\Logger;

$db_name = 'fedportal';

$username = 'fedportal';

$passwd = 'fedportal';


function get_db()
{
  global $db_name;

  global $username;

  global $passwd;

  return new PDO(
    "mysql:host=localhost;dbname=$db_name;charset=utf8", $username, $passwd,
    [
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_PERSISTENT => true,
    ]);
}

function logPdoException(PDOException $e, $message, Logger $log)
{
  $log->addError($message);
  $log->addError('Error code is ' . $e->getCode());
  $log->addError($e->getMessage());
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
