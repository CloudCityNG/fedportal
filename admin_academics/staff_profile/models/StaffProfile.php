<?php

require_once(__DIR__ . '/../../../helpers/databases.php');
require_once(__DIR__ . '/../../../helpers/app_settings.php');
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

  /**
   * Get all staff in the database
   * @return array|null
   */
  public static function getAllStaff()
  {
    $query = "SELECT * FROM staff_profile";
    $logger = new SqlLogger(self::logger(), 'Get all staff profile', $query);
    $stmt = get_db()->query($query);

    if ($stmt) {
      $logger->statementSuccess();
      $result = [];

      while ($row = $stmt->fetch()) {
        $row['password'] = 'HIDDEN';
        $result[] = $row;
      }
      $logger->dataRetrieved($result);

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

    $paramArray = [];
    foreach ($params as $param => $val) {
      $paramArray[] = "{$param}=:{$param}";
    }

    $query = "SELECT COUNT(*) FROM staff_profile WHERE " . implode(' AND ', $paramArray);
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
