<?php

require_once(__DIR__ . '/../../../helpers/databases.php');
require_once(__DIR__ . '/../../../helpers/app_settings.php');
require_once(__DIR__ . '/../../../helpers/SqlLogger.php');

class StaffCapability
{
  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('StaffCapabilityModel');
  }

  public static function getAllCapabilities(){
    $query = "select * from staff_capability";
    $logger = new SqlLogger(self::logger(), 'Get all staff capabilities', $query);
    $stmt = get_db()->query($query);

    if($stmt){
      $logger->statementSuccess();
      $result = $stmt->fetchAll();
      $logger->dataRetrieved($result);

      return $result;
    }

    $logger->noData();

    return null;
  }
}
