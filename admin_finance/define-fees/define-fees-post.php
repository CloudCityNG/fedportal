<?php

include_once(__DIR__ . '/../../helpers/databases.php');

include_once(__DIR__ . '/../../vendor/autoload.php');

include_once(__DIR__ . '/../../helpers/app_settings.php');

include_once(__DIR__ . '/../../helpers/models/StudentBilling.php');


class SetSchoolFee
{
  private $academic_year;
  private $level;
  private $dept;
  private $fee;

  private static $LOG_NAME = 'SetSchoolFee';

  public function __construct($academic_year, $level, $dept, $fee)
  {
    $this->academic_year = $academic_year;

    $this->level = $level;

    $this->dept = $dept;

    $this->fee = $fee;

    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }

  private function set_session($val)
  {
    $_SESSION['fees-info'] = $val;
  }

  private function fee_already_set()
  {

    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo(
      "About to search if we already created fee for $this->academic_year, $this->level and $this->dept."
    );

    try {
      $stmt = $db->prepare(
        'SELECT COUNT(*) FROM school_fees
        WHERE department = :dept AND
        academic_level = :level AND
        academic_year = :year ;');

      $stmt->bindValue(':dept', $this->dept);

      $stmt->bindValue(':level', $this->level);

      $stmt->bindValue(':year', $this->academic_year);

      $stmt->execute();

      $found = $stmt->fetchColumn();

      if ($found) {
        $log->info(
          "Fee already set for $this->academic_year, $this->level and $this->dept.
           We will not set a new fee."
        );

      } else {

        $log->info(
          "Fee has not been set for $this->academic_year, $this->level and $this->dept."
        );

      }

      return $found;

    } catch (PDOException $e) {

      $log->addError(
        "Error while searching for fee $this->academic_year, $this->level and $this->dept."
      );

      $log->addError($e->getMessage());

      return true;
    }

  }

  private function create_fee()
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    $log->addInfo(
      "About to create a new school fee for $this->academic_year, $this->level, $this->dept, amount = $this->fee"
    );

    try {

      $stmt = $db->prepare(
        "INSERT INTO school_fees (academic_year, academic_level, fee, department) " .
        "VALUES (?, ?, ?, ?)"
      );

      $stmt->execute([$this->academic_year, $this->level, $this->fee, $this->dept]);

      $inserted = $stmt->rowCount();

      if ($inserted) {

        $log->addInfo(
          "New school fee successfully created for $this->academic_year,
           $this->level, $this->dept, amount = $this->fee"
        );

      } else {
        $log->addInfo(
          "New school fee could not be created for $this->academic_year,
          $this->level, $this->dept, amount = $this->fee"
        );
      }

      return $inserted;

    } catch (PDOException $e) {

      $log->addError(
        "Error encountered while creating new school for $this->academic_year,
        $this->level, $this->dept, amount = $this->fee."
      );

      $log->addError($e->getMessage());
    }
  }

  public function attach_created_fee_to_students()
  {
    $bill = new StudentBilling();

    $bill->update_bills_for_level($this->level, $this->academic_year, $this->dept, $this->fee);
  }

  public function bootstrap()
  {
    if ($this->fee_already_set()) {

      $this->set_session(
        json_encode(
          [
            'error' => 'An error occurred while setting fee or fee already set for year,
            department and level. Action aborted!'
          ]
        ));

    } else if ($this->create_fee()) {

      $this->set_session(
        json_encode([
          'academic_year' => $this->academic_year,
          'level' => $this->level,
          'fee' => $this->fee,
          'dept_code' => $this->dept
        ])
      );

      $this->attach_created_fee_to_students();


    } else {

      $this->set_session('error');
    }

    session_write_close();

    $payment_setting_url = STATIC_ROOT . 'admin_finance/define-fees/';

    header("Location: $payment_setting_url");

  }
}


$academic_year = trim($_POST['academic_year']);

$level = trim($_POST['level']);

$fee = trim($_POST['fee']);

$dept = trim($_POST['department']);

$set_fee = new SetSchoolFee($academic_year, $level, $dept, $fee);

$set_fee->bootstrap();
