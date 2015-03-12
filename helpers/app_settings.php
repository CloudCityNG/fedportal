<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 05-Feb-15
 * Time: 1:04 AM
 */
define('STATIC_ROOT', '/fedportal/');

define('DASHBOARD_HOME', '/fedportal/student_dashboard.php');

include_once(__DIR__ . '/../vendor/autoload.php');

use Monolog\Logger;

use Monolog\Handler\StreamHandler;

function get_logger($name)
{

  $log = new Logger($name);

  $log->pushHandler(new StreamHandler(__DIR__ . '/../out_log.log', Logger::DEBUG));

  return $log;
}

function get_log_file()
{
  return __DIR__ . '/../out_log.log';
}

function get_photo_dir()
{
  $uploadsDirectory =  __DIR__ . '/../photo_files/' ;

  if (!file_exists($uploadsDirectory)) {
    mkdir($uploadsDirectory);
  }

  return $uploadsDirectory;}
