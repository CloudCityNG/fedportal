<?php

define('STATIC_ROOT', '/fedportal/');

define('SESSION_TIME_OUT', 1800);
define('SESSION_TIME_OUT_ALERT', 1500);

define('DB_NAME', 'fedportal');
define('DB_USERNAME', 'fedportal');
define('DB_PASSWORD', 'fedportal');

include_once(__DIR__ . '/../vendor/autoload.php');

use Monolog\Logger;

use Monolog\Handler\StreamHandler;

/**
 * @param string $name
 * @return Logger
 */
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
  $uploadsDirectory = __DIR__ . '/../photo_files/';

  if (!file_exists($uploadsDirectory)) {
    mkdir($uploadsDirectory);
  }

  return $uploadsDirectory;
}

function path_to_link($path)
{
  if (!file_exists($path)) {
    return '';
  }

  $unix_path = str_replace('\\', '/', realpath($path));

  $root_pos = strpos($unix_path, STATIC_ROOT);

  return substr($unix_path, $root_pos) . (is_dir($path) ? '/' : '');
}

/**
 * checks whether the session has expired.
 * Returns 'true' if session is not old and invalid, 'false' otherwise
 *
 * @param string $appSessionName - name of the session used by the app
 * @return bool
 */
function sessionAgeValid($appSessionName)
{
  $lastActivitySessionName = 'LAST-ACTIVITY-' . $appSessionName;

  if (isset($_SESSION[$lastActivitySessionName]) &&
    (time() - $_SESSION[$lastActivitySessionName] > SESSION_TIME_OUT)
  ) {

    if (isset($_SESSION[$appSessionName])) unset($_SESSION[$appSessionName]);
    return false;
  }
  $_SESSION[$lastActivitySessionName] = time();

  return true;
}
