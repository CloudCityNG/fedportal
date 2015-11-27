<?php

define('DB_NAME', 'fedportal');
define('DB_USERNAME', 'fedportal');
define('DB_PASSWORD', 'fedportal');

define('STATIC_ROOT', '/fedportal/');

define('BLANK_IMAGE_PATH', STATIC_ROOT . 'photo_files/_blank.png');

define('SESSION_TIME_OUT', 1800);
define('SESSION_TIME_OUT_ALERT', 1500);

define('SCHOOL_NAME', 'Federal School of Dental Technology And Therapy');
define('SCHOOL_WEBSITE', 'www.fedsdtten.edu.ng');
define('SCHOOL_ADDRESS', 'Trans-Ekulu, P.M.B. 01473, Enugu');

//auth sessions
define('STUDENT_PORTAL_AUTH_KEY', 'REG_NO');

define('STAFF_USER_SESSION_KEY', 'jhhdd7HG5dhdhd{}VDE{}+FFqbbch');
define('STAFF_USER_SESSION_VALUE', 'jhhdd7HG5dhdhd{}VDE{}+FFqbbch');
define('USER_AUTH_SESSION_KEY', 'USER_d0ks25_50?@#^&SESSION_KEY');
define('STAFF_CAPABILITY_KEY', 'MjhsvsYBBB50?@#^&*/KKgqvnop');

define('LAST_ACTIVITY_AUTH_PREFIX_KEY', 'LAST-ACTIVITY-');

include_once(__DIR__ . '/../vendor/autoload.php');

use Monolog\Logger;

use Monolog\Handler\RotatingFileHandler;

/**
 * @param string $name
 * @return Logger
 */
function get_logger($name)
{

  $log = new Logger($name);

  $logDir = __DIR__ . '/../out_logs';

  if (!file_exists($logDir)) mkdir($logDir);

  $log->pushHandler(new RotatingFileHandler($logDir . '/out_log.log', 0, Logger::DEBUG));

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

/**
 * @param $path
 * @param bool|false $version - whether to add version information to the path generated
 * @return string
 */
function path_to_link($path, $version = false)
{
  if (!file_exists($path)) {
    return '';
  }

  $absPath = realpath($path);
  $unix_path = str_replace('\\', '/', $absPath);

  $root_pos = strpos($unix_path, STATIC_ROOT);

  $versionArg = $version ? '?v=' . filemtime($absPath) : '';

  return substr($unix_path, $root_pos) . (is_dir($path) ? '/' : '') . $versionArg;
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
  $lastActivitySessionName = LAST_ACTIVITY_AUTH_PREFIX_KEY . $appSessionName;

  if (isset($_SESSION[$lastActivitySessionName]) && (time() - $_SESSION[$lastActivitySessionName] > SESSION_TIME_OUT)) {

    unset($_SESSION[$appSessionName]);
    unset($_SESSION[USER_AUTH_SESSION_KEY]);
    return false;
  }

  $_SESSION[$lastActivitySessionName] = time();

  return true;
}

/**
 * Take a php array and convert to array suitable for use in database query e.g in WHERE CLAUSE or INSERT attribute
 * values
 *
 * @param array $phpArray - of the form [0: mixed, 1: mixed, 2: mixed....]
 * @return string - of the form ('string', 'string', 'string'..)
 */
function toDbArray(array $phpArray)
{
  $returned = '(';
  foreach ($phpArray as $el) {
    $returned .= "'{$el}', ";
  }
  return trim($returned, ' ,') . ')';
}

/**
 * Take a php array and convert to array suitable for use in database INSERT for column names e.g INSERT INTO table_x
 * (colName1, colName2...,colNameN)
 *
 * @param array $phpArray - of the form [0: mixed, 1: mixed, 2: mixed....]
 * @return string - of the form (string, string, string..)S
 */
function toDbColArray(array $phpArray)
{
  $returned = '(';
  foreach ($phpArray as $el) {
    $returned .= "{$el}, ";
  }
  return trim($returned, ' ,') . ')';
}
