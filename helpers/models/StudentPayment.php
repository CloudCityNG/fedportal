<?php

include_once(__DIR__ . '/../databases.php');

include_once(__DIR__ . '/../app_settings.php');

class StudentPayment
{

  private $log_name = "StudentPaymentModel";
  private static  $LOG_NAME = "StudentPaymentModel";

  public function save_payment(Array $pay_details)
  {

    $db = get_db();

    $log = get_logger($this->log_name);

    $reg_no = $pay_details['reg_no'];

    $academic_year = isset($pay_details['academic_year']) ? $pay_details['academic_year'] : null;

    $level = $pay_details['level'];

    $dept_code = $pay_details['dept_code'];

    $amount = $pay_details['amount'];

    $remark = $pay_details['remark'];

    $receipt_no = $pay_details['receipt_no'];

    $log->addInfo(
      "About to insert payment received from student $reg_no into database.
       Payment detail is: " . print_r($pay_details, true)
    );

    try {

      $academic_year_type = $academic_year ? PDO::PARAM_STR : PDO::PARAM_NULL;

      $stmt = $db->prepare("INSERT INTO student_payment (reg_no, academic_year, level, dept_code,
                                                       amount, remark, receipt_no, created_at)
                            VALUES (:reg_no, :academic_year, :level, :dept_code,
                                    :amount, :remark, :receipt_no, FROM_UNIXTIME(:created_at) ) ;");

      $now = time();

      $stmt->bindValue(':reg_no', $reg_no);
      $stmt->bindValue(':academic_year', $academic_year, $academic_year_type);
      $stmt->bindValue(':level', $level);
      $stmt->bindValue(':dept_code', $dept_code);
      $stmt->bindValue(':amount', $amount);
      $stmt->bindValue(':remark', $remark);
      $stmt->bindValue(':receipt_no', $receipt_no);
      $stmt->bindValue(':created_at', $now);

      $stmt->execute();

      $rowCount = $stmt->rowCount();

      $rowId = $db->lastInsertId();

      if ($rowCount) {

        $log->addInfo(
          "Payment received from student $reg_no successfully saved in database. Row id is $rowId.");

        $pay_details['id'] = $rowId;

        $pay_details['academic_year'] = $academic_year;

        $pay_details['created_at'] = date('d-m-Y H:m:s', time());

        return $pay_details;

      } else {

        $log->addWarning(
          "Something went wrong. Payment received from student $reg_no could not be saved in database.");

        return null;
      }


    } catch (PDOException $e) {
      logPdoException(
        $e,
        "An error occurred while saving payment received from student $reg_no.",
        $log
      );

      return null;
    }

  }

  /**
   * @param array $data
   * @return null|array
   */
  public static function get_fee_for_dept_level_session(array $data)
  {
    $query = "SELECT fee FROM school_fees
              WHERE department = :department
              AND academic_year = :academic_year
              AND academic_level = :academic_level";

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo("About to get fee with query: {$query} and params: ", $data);

    $stmt = get_db()->prepare($query);

    if ($stmt->execute($data)) {
      $fee = $stmt->fetch();

      $fee = $fee ? $fee['fee'] : null;

      $log->addInfo("Statement executed successfully, fee is {$fee}");
      return $fee;
    }

    $log->addWarning("Statement did not execute successfully.");
    return null;
  }

}
