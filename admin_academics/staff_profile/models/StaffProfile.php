<?php

require_once(__DIR__ . '/../../../helpers/app_settings.php');
require_once(__DIR__ . '/../../../helpers/databases.php');
require_once(__DIR__ . '/../../../helpers/SqlLogger.php');

use Carbon\Carbon;

class StaffProfile
{
  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('StaffProfileModel');
  }

  private static function scramblePassword(array $data)
  {
    $result = [];

    foreach ($data as $row) {
      if (isset($row['password'])) {
        $row['password'] = 'HIDDEN';
        $result[] = $row;
      }
    }

    return $result;
  }

  /**
   * Get staff in the database with optional filter
   * @param array $filter
   * @return array|null
   */
  public static function getStaff(array $filter = null)
  {
    $query = "SELECT * FROM staff_profile";

    if ($filter) $query .= ' WHERE ' . getDbBindParamsFromColArray(array_keys($filter));
    else $filter = [];

    $logger = new SqlLogger(self::logger(), 'Get staff profile', $query, $filter);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($filter)) {
      $logger->statementSuccess();
      $result = $stmt->fetchAll();
      $logger->dataRetrieved(self::scramblePassword($result));
      return $result;
    }

    $logger->noData();
    return null;
  }

  /**Checks if staff exists given the array of attributes and values
   * @param array $params - a mapping of legal staff_profile attributes to values we want to check
   * @return boolean - that indicates whether the staff exists or not
   */
  public static function staffExists(array $params)
  {
    if (!is_array($params) || !count($params)) return 0;

    $query = "SELECT COUNT(*) FROM staff_profile WHERE " . getDbBindParamsFromColArray(array_keys($params));
    $logger = new SqlLogger(self::logger(), 'Check if staff profile exists', $query, $params);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $logger->statementSuccess();
      $result = $stmt->fetchColumn();
      $logger->dataRetrieved([$result]);
      return $result;
    }

    $logger->noData();

    return 0;
  }

  /**
   * @param array $changes - a mapping of attribute names to their values. These are the attributes we wish to update
   * @param array|null $filter - a mapping of attribute names to their values that will be used in the WHERE clause.
   * @return int - 1 means update success, 0 means failure
   */
  public static function updateProfile(array $changes, array $filter = null)
  {
    if (isset($changes['password'])) $changes['password'] = password_hash($changes['password'], PASSWORD_DEFAULT);

    $changesBindParams = getDbBindParamsFromColArray(array_keys($changes), ' , ');
    $query = "UPDATE staff_profile SET {$changesBindParams} ";

    if ($filter) $query .= ' WHERE ' . getDbBindParamsFromColArray(array_keys($filter));
    else $filter = [];

    $params = array_merge($changes, $filter);
    $logger = new SqlLogger(self::logger(), 'Update staff profile', $query, $params);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($params)) {
      $logger->statementSuccess();
      $result = 1;
      $logger->dataRetrieved([$result]);
      return $result;
    }

    $logger->noData();
    return 0;
  }

  /**
   * Create a staff profile
   * @param array $params - staff attributes to values mapping
   * @return array|null - returns a mapping of staff attributes to values including database ID of newly created
   *   profile as well creation time. Returns null if we are unable to create staff profile
   */
  public static function createProfile(array $params)
  {
    $db = get_db();
    $now = Carbon::now();
    $tableColArray = [];
    $tableColsParamArray = [];
    $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);

    foreach ($params as $param => $val) {
      $tableColArray[] = $param;
      $tableColsParamArray[] = ':' . $param;
    }

    $tableColArray = array_merge($tableColArray, ['created_at', 'updated_at']);
    $tableColsParamArray = array_merge($tableColsParamArray, ["'$now'", "'$now'"]);
    $tableColArray = toDbColArray($tableColArray);
    $tableColsParamArray = toDbColArray($tableColsParamArray);
    $query = "INSERT INTO staff_profile {$tableColArray} VALUES {$tableColsParamArray}";
    $logger = new SqlLogger(self::logger(), 'Create staff profile', $query, $params);
    $stmt = $db->prepare($query);

    if ($stmt->execute($params)) {
      $logger->statementSuccess();
      $params['id'] = $db->lastInsertId();
      $params['created_at'] = $now;
      $params['updated_at'] = $now;
      $logger->dataRetrieved($params);
      return $params;
    }

    $logger->noData();
    return null;
  }

  /**
   * @param $username
   * @return int - 1 means profile delete success while 0 indicates failure
   */
  public static function deleteProfile($username)
  {
    $query = "DELETE FROM staff_profile WHERE username=:username";
    $param = ['username' => $username];
    $logger = new SqlLogger(self::logger(), 'Delete staff profile completely', $query, $param);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($param)) {
      $logger->statementSuccess();
      return 1;
    }
    $logger->noData();
    return 0;
  }
}
