<?php
/**
 * Created by IntelliJ IDEA.
 * User: maneptha
 * Date: 09-Feb-15
 * Time: 1:20 PM
 */

require_once(__DIR__ . '/../databases.php');

require_once(__DIR__ . '/../app_settings.php');

require_once(__DIR__ . '/StudentProfile.php');


class StudentBilling
{

  private static $LOG_NAME = 'StudentBilling';

  private function bill_exists($reg_no, $academic_year, $level, $dept_code)
  {
    $db = get_db();

    $stmt = $db->prepare(
      "SELECT COUNT(*) FROM student_billing
       WHERE reg_no = ? AND
       academic_year = ? AND
       level = ? AND
       department_code = ?"
    );

    $stmt->execute([$reg_no, $academic_year, $level, $dept_code]);

    return $stmt->fetchColumn();

  }

  public function update_bill($reg_no, $academic_year, $level, $amount, $dept_code)
  {
    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    try {
      $stmt = $db->prepare(
        "UPDATE student_billing SET amount = :new_amount
         WHERE amount IS NULL AND
         reg_no = :reg_no AND
         academic_year = :year AND
         level = :level AND
         department_code = :dept"
      );

      $stmt->bindValue(':new_amount', $amount);

      $stmt->bindValue(':reg_no', $reg_no);

      $stmt->bindValue(':year', $academic_year);

      $stmt->bindValue(':level', $level);

      $stmt->bindValue(':dept', $dept_code);

      $stmt->execute();

      $rowCount = $stmt->rowCount();

      if ($rowCount) {

        $log->addInfo(
          "Student billing successfully updated for registration number = $reg_no, $academic_year
          $level, $dept_code and amount = $amount."
        );

      } else {

        $log->addInfo(
          "Student billing could not be updated for registration number $reg_no, $academic_year
          $level, $dept_code and amount = $amount."
        );

      }

      return $rowCount;

    } catch (PDOException $e) {

      logPdoException(
        $e,
        "Error while trying to update bill amount for student with registration
         number $reg_no, amount $amount, $level, $dept_code and $academic_year",
        $log
      );
    }
  }

  public function insert_bill($reg_no, $academic_year, $level, $dept_code)
  {
    /*
     * We create a billing record for student. This is usually done at the beginning of the session/academic year
     * when students choose their level and sign up for courses.
     */

    $db = get_db();

    $log = get_logger(self::$LOG_NAME);

    try {

      //we first retrieve the amount that has been set for the student's department, level and academic year

      $stmt_fee = $db->prepare(
        "SELECT fee FROM school_fees " .
        "WHERE department = ? AND " .
        "academic_year = ? AND " .
        "academic_level = ?"
      );

      $stmt_fee->execute([$dept_code, $academic_year, $level]);

      $amount = $stmt_fee->rowCount() ? $stmt_fee->fetch(PDO::FETCH_ASSOC)['fee'] : null;

      if ($amount) {

        $log->info(
          "school fee $amount successfully retrieved for department code '$dept_code', " .
          "academic year '$academic_year', academic level '$level'\n"
        );

      } else {

        $log->addInfo(
          "School fee amount not found for $dept_code, $academic_year, and $level. May be finance admin has not
          set fee for the 3 parameters. In any case, we will go ahead and insert NULL as amount for student $reg_no.
          When finance admin sets fee eventually, we will update amount from NULL to the fee set by finance admin."
        );
      }

      if ($this->bill_exists($reg_no, $academic_year, $level, $dept_code) && $amount) {

        return $this->update_bill($reg_no, $academic_year, $level, $amount, $dept_code);

      }

      $amount_type = $amount ? PDO::PARAM_STR : PDO::PARAM_NULL;

      $stmt_billing = $db->prepare(
        "INSERT INTO
         student_billing(reg_no, academic_year, level, amount, department_code)
         VALUES (?, ?, ?, ?, ?)"
      );

      $stmt_billing->bindValue(1, $reg_no);
      $stmt_billing->bindValue(2, $academic_year);
      $stmt_billing->bindValue(3, $level);
      $stmt_billing->bindValue(4, $amount, $amount_type);
      $stmt_billing->bindValue(5, $dept_code);

      $stmt_billing->execute();

      $rowCount = $stmt_billing->rowCount();

      if ($rowCount) {

        $log->info(
          "Student billing successfully inserted for student $reg_no, amount = $amount, $academic_year, $level."
        );

      } else {

        $log->info(
          "Student billing failed to insert for amount = $amount, $reg_no, $academic_year, $level."
        );

      }

      return $rowCount;

    } catch (PDOException $e) {
      $log->addError("Error occurred while attempting to insert student bill");

      $log->addError($e->getMessage());

      return null;

    }

  }

  public function update_bills_for_level($level, $academic_year, $dept_code, $amount)
  {
    /*
     * when the finance admin sets fee for a particular level (say OND1) in a particular department
     * (say dental technology) and session (e.g 2014/2015 academic year) (note that all these 3 are unique together
     * in the student billing table), this method will be called to update students who are already signed up
     * for courses for the 3 given variables.  This is the whole scenario:
     *
     * A student signs up for courses. If finance admin had not set up fee for that year, dept and level, a row
     * is inserted into student_billing table with student matriculation number, academic session, dept and level,
     * but with an amount of null. When finance admin finally sets fee for the 3 parameters, the row will be updated
     * with the amount set.
     *
     * Note that the 3 parameters/variables are (1) academic session/year (2) level (3) department.
     */
    $log = get_logger('StudentBilling');

    $db = get_db();

    $log->addInfo("About to do bulk update of school fee for students in $level,
                   $academic_year and $dept_code");

    try {
      $stmt = $db->prepare(
        "UPDATE student_billing SET amount = :AMOUNT
         WHERE amount IS NULL AND
         level = :LEVEL AND
         academic_year = :YEAR AND
         department_code = :DEPT ");

      $stmt->execute([
        ':AMOUNT' => $amount,

        ':LEVEL' => $level,

        ':YEAR' => $academic_year,

        ':DEPT' => $dept_code
      ]);

      $rowCount = $stmt->rowCount();

      if ($rowCount) {

        $log->addInfo("Bill amount $amount successfully updated for students who have
                       registered for academic session $academic_year in $dept_code and $level.
                       A total of $rowCount student(s) updated.");

      } else {

        $log->addInfo("Bill amount $amount could not be updated for students who
                       have registered for academic session $academic_year in $dept_code and $level.
                       May be students have not yet registered for courses for the semester and academic
                       year in the department or an unknown error has occurred.");
      }

      return $rowCount;

    } catch (PDOException $e) {

      $log->addError("Something went wrong. Bill amount $amount could not be updated for students who
                       have registered for academic session $academic_year in $dept_code and $level.");

      $log->addError($e->getMessage());

    }

  }

  public function get_owing($reg_no)
  {
    $bills = $this->get_sum_bills_for_student($reg_no);

    $payments = $this->get_sum_payments_for_student($reg_no);

    $total_owing = 0;

    if (($bills !== null) && ($payments !== null)) {

      $total_owing = $payments - $bills;
    }

    return $total_owing;
  }

  private function get_sum_bills_for_student($reg_no)
  {
    $db = get_db();

    $log = get_logger('StudentBilling');

    $log->addInfo("About to get how much is owed by student $reg_no.");

    $amount_owing = 0;

    try {

      $stmt_bills = $db->query("SELECT sum(amount) FROM student_billing WHERE reg_no = '$reg_no' ");

      $retrieved_owing = $stmt_bills->fetch(PDO::FETCH_NUM)[0];

      if ($retrieved_owing) {

        $amount_owing = floatval($retrieved_owing);

        $log->addInfo("Owing amount $amount_owing successfully retrieved for student $reg_no");

      } else {

        $log->info("No amount owing found for student $reg_no.
                    May be fee has not been set or student does not exist.");
      }

      $stmt_bills->closeCursor();

    } catch (PDOException $e) {

      $log->addError("Error occurred while retrieving owing for student $reg_no");

      $log->addError($e->getMessage());

    }

    return $amount_owing;

  }

  private function get_sum_payments_for_student($reg_no)
  {
    $db = get_db();

    $log = get_logger('StudentBilling');

    $log->addInfo("About to get sum of payments as at now for student $reg_no");

    $amount = 0;

    try {

      $stmt = $db->query("select SUM(amount) from student_payment where reg_no = '$reg_no'");

      if ($stmt) {

        $retrieved = floatval($stmt->fetch(PDO::FETCH_NUM)[0]);

        if ($retrieved) {

          $amount = floatval($retrieved);

          $log->addInfo("Sum of payments by student $reg_no as at now is $amount");

        } else {

          $log->addWarning("No payments yet by student $reg_no or may be student does not exists");
        }

      } else {

        $log->info("Query could not be ran for sum of payments by student $reg_no");
      }

      $stmt->closeCursor();

    } catch (PDOException $e) {

      logPdoException(
        $e,
        "PDO error occurred while retrieving sum of payments by student $reg_no",
        $log
      );

    }

    return $amount;
  }

  public function get_debtors()
  {
    $db = get_db();

    $log = get_logger("student-billing");

    $log->addInfo("About to get data for students currently owing the school");

    $students = [];

    $stmt_all_reg = $db->query(
      "SELECT personalno FROM freshman_profile"
    );

    while ($row = $stmt_all_reg->fetch(PDO::FETCH_ASSOC)) {
      $reg_no = $row['personalno'];

      $owing = $this->get_owing($reg_no);

      if ($owing === 0) {
        continue;
      }

      $student = new StudentProfile($reg_no);

      $student->owing = $owing;

      $students[] = $student;

    }

    $stmt_all_reg->closeCursor();

    return $students;
  }

}
