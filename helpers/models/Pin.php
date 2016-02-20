<?php

require_once(__DIR__ . '/../../helpers/app_settings.php');
require_once(__DIR__ . '/../databases.php');
require_once(__DIR__ . '/../../helpers/SqlLogger.php');

class Pin
{
  /**
   * @return \Monolog\Logger
   */
  private static function logger()
  {
    return get_logger('PinModel');
  }

  /**
   * It is not wise to log passwords to disk because of security concerns
   * @param array $params
   * @return array - $params is returned with the password hidden
   */
  private static function hidePassword(array $params)
  {
    if (isset($params['pass'])) $params['pass'] = 'HIDDEN';
    return $params;
  }

  /**
   * Checks if records exist in the database for the given filter
   * @param array $filter
   * @return null|string
   */
  public static function exists(array $filter)
  {
    $dbFilter = getDbBindParamsFromColArray(array_keys($filter));
    $query = "SELECT COUNT(*) FROM pin_table WHERE {$dbFilter}";
    $logger = new SqlLogger(self::logger(), 'Check if pin exists', $query, self::hidePassword($filter));
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($filter)) {
      $logger->statementSuccess();
      $result = $stmt->fetchColumn();
      $logger->dataRetrieved([$result]);
      return $result;
    }

    $logger->noData();
    return null;
  }
}
