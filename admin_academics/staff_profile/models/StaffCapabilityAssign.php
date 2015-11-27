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

    if($stmt){
      $logger->statementSuccess();
      return 1;
    }
    return 0;
  }
}
