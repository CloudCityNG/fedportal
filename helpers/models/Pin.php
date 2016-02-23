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
   * Get pin from database and optionally filter with param $filter
   * @param null|array $filter
   * @return null|string
   */
  public static function get(array $filter = null)
  {
    $query = 'SELECT * FROM pin_table ';
    $existsOnly = false;
    $params = $filter;

    if ($filter && count($filter)) {
      if (isset($filter['__exists']) && $filter['__exists']) {
        $query = 'SELECT COUNT(*) FROM pin_table ';
        $existsOnly = true;
      }

      unset($filter['__exists']);
      $dbFilter = getDbBindParamsFromColArray(array_keys($filter));
      $query .= " WHERE {$dbFilter}";

    } else $filter = [];

    $logger = new SqlLogger(self::logger(), 'Get student registration pin data', $query, self::hidePassword($params));
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($filter)) {
      $logger->statementSuccess();

      if ($existsOnly) {
        $result = $stmt->fetchColumn();
        $logger->dataRetrieved($result);
        return $result;
      }

      $result = $stmt->fetchAll();
      $logger->dataRetrieved([$result]);

      return $result;
    }

    $logger->noData();
    return null;
  }
}
