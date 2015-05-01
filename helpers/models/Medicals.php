<?php

require_once(__DIR__ . '/../databases.php');

require_once(__DIR__ . '/../app_settings.php');


class Medicals
{
  private static $LOG_NAME = 'Medicals';

  public static function exists($reg_no = null)
  {
    $db = get_db();

    $stmt = $db->prepare(
      "SELECT COUNT(*) FROM medical_info WHERE reg_no = ?"
    );

    $stmt->execute([$reg_no]);

    return $stmt->fetchColumn();
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
