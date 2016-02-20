<?php

require_once(__DIR__ . '/../databases.php');
require_once(__DIR__ . '/../app_settings.php');
require_once(__DIR__ . '/../SqlLogger.php');


class Medicals
{
  private static $LOG_NAME = 'Medicals';

  private static function logger()
  {
    return get_logger('MedicalsModel');
  }

  /**
   * Get students medical record and optionally filter by $filter param
   * @param array|null $filter
   * @return null|array
   */
  public static function get(array $filter = null)
  {
    $query = 'SELECT * FROM medical_info ';
    $existsOnly = false;

    if ($filter) {
      if (isset($filter['__exists'])) {

        if ($filter['__exists']) {
          $existsOnly = true;
          $query = 'SELECT COUNT(*) FROM medical_info ';
        }

        unset($filter['__exists']);
      }

      $dbBindParams = getDbBindParamsFromColArray(array_keys($filter));
      $query .= " WHERE {$dbBindParams}";

    } else $filter = [];

    $logger = new SqlLogger(self::logger(), 'Get student medical record:', $query, $filter);
    $stmt = get_db()->prepare($query);

    if ($stmt->execute($filter)) {
      $logger->statementSuccess();

      if ($existsOnly) {
        $result = $stmt->fetchColumn();
        $logger->dataRetrieved($result);
        return $result;
      }

      $result = $stmt->fetch();
      $logger->dataRetrieved($result);
      return $result;
    }

    $logger->noData();
    return null;
  }

  public static function save($inputs)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $query = "INSERT INTO medical_info(reg_no, blood_group, genotype,
                                       allergies, medical_conditions,
                                       doctor_name, doctor_mobile_phone,
                                       doctor_address, created_at)
              VALUES (:reg_no, :blood_group, :genotype, :allergies, :medical_conditions,
                      :doctor_name, :doctor_mobile_phone, :doctor_address, NOW())";

    $log->addInfo("About to create medical record with query: $query and inputs: ", $inputs);

    try {
      $stmt = $db->prepare($query);

      foreach ($inputs as $key => $val) {
        if (in_array($key, ['reg_no', 'blood_group', 'genotype'])) {
          $stmt->bindValue(':' . $key, $val);

        } else {
          $stmt->bindValue(':' . $key, $val, trim($val) ? PDO::PARAM_STR : PDO::PARAM_NULL);
        }
      }

      $stmt->execute();

      $log->addInfo("Medical record successfully saved.");

      return $stmt->rowCount();

    } catch (PDOException $e) {

      logPdoException(
        $e,
        "Error occurred while creating medical record.",
        $log
      );

    }

    return 0;

  }

}
