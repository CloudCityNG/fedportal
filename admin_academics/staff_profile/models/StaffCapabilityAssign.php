<?php

require_once(__DIR__ . '/../../../helpers/databases.php');
require_once(__DIR__ . '/../../../helpers/app_settings.php');
require_once(__DIR__ . '/../../../helpers/SqlLogger.php');

use Carbon\Carbon;

class StaffCapabilityAssign
{
  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('StaffCapabilityAssignModel');
  }

  /**
   * @param array $staffIdCapabilities - an array of the form:
   *    [ [staffId, capabilityId], [staffId, capabilityId], [staffId, capabilityId].. ]
   * @return int - 1 means success and 0 means failure
   */
  public static function create(array $staffIdCapabilities)
  {
    $now = Carbon::now();
    $capabilities = [];

    foreach ($staffIdCapabilities as $staffIdCapability) {
      $capabilities[] = toDbArray(array_merge($staffIdCapability, [$now, $now]));
    }

    $capabilities = implode(',', $capabilities);
    $query = "INSERT INTO staff_capability_assign
                (staff_profile_id, staff_capability_id, created_at, updated_at) VALUES {$capabilities}";
    $logger = new SqlLogger(self::logger(), 'Assign capabilities to staff', $query);
    $stmt = get_db()->query($query);

    if ($stmt) {
      $logger->statementSuccess();
      return 1;
    }
    return 0;
  }

  /**
   * Get capabilities that have been assigned to staff and filter by $filter if specified.
   * @param array|null $filter - mapping to table columns to their values
   * @return array|null
   */
  public static function getCapabilities(array $filter = null)
  {
    $query = "SELECT * FROM staff_capability_assign";

    if ($filter) $query .= ' WHERE ' . getDbBindParamsFromColArray(array_keys($filter));
    else $filter = [];

    $logger = new SqlLogger(self::logger(), 'Get capabilities that have been assigned to staff', $query, $filter);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($filter)) {
      $logger->statementSuccess();
      $result = $stmt->fetchAll();
      $logger->dataRetrieved($result);

      return $result;
    }

    $logger->noData();

    return null;
  }

  /**
   * Delete capabilities assigned to staff
   *
   * @param array|null $filter
   * @return int - 1 means success and 0 means failure
   */
  public static function deleteCapabilities(array $filter = null)
  {
    $query = "DELETE FROM staff_capability_assign ";

    if ($filter) $query .= ' WHERE ' . getDbBindParamsFromColArray(array_keys($filter));
    else $filter = [];

    $logger = new SqlLogger(self::logger(), 'Delete capabilities assigned to staff', $query, $filter);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($filter)) {
      $logger->statementSuccess();
      $result = 1;
      $logger->dataRetrieved([$result]);
      return $result;
    }

    $logger->noData();
    return 0;
  }
}
